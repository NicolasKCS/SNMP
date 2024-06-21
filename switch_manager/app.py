from flask import Flask, render_template, redirect, url_for, request, session, flash
from flask_mysqldb import MySQL
import bcrypt
from pysnmp.hlapi import *

app = Flask(__name__)
app.config.from_object('config.Config')

mysql = MySQL(app)

@app.route('/', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        username = request.form['username']
        password = request.form['password'].encode('utf-8')

        cur = mysql.connection.cursor()
        cur.execute("SELECT * FROM users WHERE username=%s", [username])
        user = cur.fetchone()
        cur.close()

        if user and password == user[2].encode('utf-8'):
            session['username'] = username
            return redirect(url_for('dashboard'))
        else:
            flash('Invalid credentials', 'danger')
    return render_template('login.html')

@app.route('/dashboard')
def dashboard():
    if 'username' in session:
        cur = mysql.connection.cursor()
        cur.execute("SELECT * FROM ports")
        ports = cur.fetchall()
        cur.close()
        return render_template('dashboard.html', ports=ports)
    return redirect(url_for('login'))

@app.route('/toggle_port/<int:port_id>')
def toggle_port(port_id):
    if 'username' in session:
        cur = mysql.connection.cursor()
        cur.execute("SELECT status FROM ports WHERE id=%s", [port_id])
        port = cur.fetchone()
        
        new_status = 'closed' if port[0] == 'open' else 'open'
        cur.execute("UPDATE ports SET status=%s WHERE id=%s", (new_status, port_id))
        mysql.connection.commit()
        cur.close()

        # Use SNMP to change the port status on the switch
        change_port_status(port_id, new_status)

        return redirect(url_for('dashboard'))
    return redirect(url_for('login'))

def change_port_status(port_id, status):
    # Placeholder SNMP logic to change port status
    # Replace with actual SNMP commands
    errorIndication, errorStatus, errorIndex, varBinds = next(
        setCmd(SnmpEngine(),
               CommunityData('public', mpModel=0),
               UdpTransportTarget(('localhost', 161)),
               ContextData(),
               ObjectType(ObjectIdentity('1.3.6.1.2.1.2.2.1.7.' + str(port_id)), Integer(1 if status == 'open' else 2)))
    )

    if errorIndication:
        print(errorIndication)
    elif errorStatus:
        print('%s at %s' % (errorStatus.prettyPrint(), errorIndex and varBinds[int(errorIndex) - 1] or '?'))

if __name__ == '__main__':
    app.run(debug=True)
