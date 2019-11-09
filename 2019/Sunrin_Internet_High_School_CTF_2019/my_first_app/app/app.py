from flask import Flask, session, url_for, request, redirect, render_template, flash
from model import *

import urllib.parse
# made by munsiwoo

app = Flask(__name__)
app.config['SESSION_TYPE'] = 'memcached'
app.config['SECRET_KEY'] = '**secret**'

@app.route('/')
def home() :
	avatar = None
	if 'uid' in session :
		avatar = get_profile(session['uid'])['avatar']
	return render_template('home.html', session=session, avatar=avatar)


@app.route('/login', methods=['GET', 'POST'])
def login() :
	if 'username' in session :
		return redirect(url_for('home'))

	if request.method == 'POST' :
		username = request.form['username']
		password = request.form['password']
		result = user_login(username, password)

		if result :
			session['uid'] = result['uid']
			session['username'] = result['username']	
			return redirect(url_for('home'))
		else :
			flash('Incorrect username or password!')
			return redirect(url_for('login'))
	else :
		return render_template('login.html')


@app.route('/register', methods=['GET', 'POST'])
def register() :
	if 'username' in session :
		return redirect(url_for('home'))

	if request.method == 'POST' :
		username = request.form['username']
		password = request.form['password']
		comment = request.form['comment']	
	
		if user_register(username, password, comment) :
			flash('Successful registration!')
			return redirect(url_for('login'))
		else :
			flash('Username already exists!')
			return redirect(url_for('register'))
	else :
		return render_template('register.html')


@app.route('/profile', methods=['GET', 'POST'])
def profile() :
	if 'username' not in session :
		flash('Please first login!')
		return redirect(url_for('login'))

	if request.method == 'POST' :
		avatar = request.form['avatar']
		password = request.form['password']
		
		if update_profile(session['uid'], avatar, password) :
			flash('Your profile update was successful.')
		else :
			flash('Your avatar is invalid.')
		
		return redirect('/profile')

	profile = get_profile(session['uid'])

	if 'avatar' not in request.args :
		avatar = urllib.parse.quote(profile['avatar'])
		return redirect('/profile?avatar={}'.format(avatar))
	
	user = {		
		'avatar': request.args['avatar'],
		'comment': profile['comment'],
		'username': profile['username'],
		'password': profile['password'],
		'uid': profile['uid']
	}
	
	return render_template('profile.html', user=user)


@app.route('/report', methods=['GET', 'POST'])
def report() :
	if 'username' not in session :
		flash('Please first login!')
		return redirect(url_for('login'))
	
	if request.method == 'POST' :
		url = request.form['url']
		
		if post_report(url) :
			flash('Your report has been sent to admin.')
		else :
			flash('Invalid URL.')

		return redirect(url_for('report'))

	return render_template('report.html')


@app.route('/logout')
def logout() :
	if 'username' not in session :
		return redirect(url_for('home'))

	session.pop('username', None)
	session.pop('uid', None)
	return redirect(url_for('home'))


@app.route('/board')
def board() :
	if 'username' not in session :
		flash('Please first login!')
		return redirect(url_for('login'))
	
	post_list = get_post_list()
	return render_template('board.html', post_list=post_list)


@app.route('/read/<pid>')
def read_post(pid) :
	if 'username' not in session :
		flash('Please first login!')
		return redirect(url_for('login'))

	post = get_post(pid)
	return render_template('read.html', post=post)


@app.route('/write', methods=['GET', 'POST'])
def write_post() :
	if 'username' not in session :
		flash('Please first login!')
		return redirect(url_for('login'))

	if request.method == 'POST' :
		title = request.form['title']
		contents = request.form['contents']
		username = session['username']
		avatar = get_profile(session['uid'])['avatar']

		insert_post(title, contents, username, avatar)

		return redirect(url_for('board'))

	return render_template('write.html')


if __name__ == '__main__' :
	app.run(host='0.0.0.0', port='5000')
