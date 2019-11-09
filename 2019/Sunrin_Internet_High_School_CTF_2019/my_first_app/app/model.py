from flask import request
from random import shuffle
from config import *

def user_login(username, password) :
	conn = connect_db()

	conn.row_factory = dict_factory
	c = conn.cursor()

	query = 'select * from users where username=? and password=?'
	c.execute(query, (username, password,))
	result = c.fetchone()
	
	c.close()

	if result :
		return result
	
	return False


def user_register(username, password, comment) :
	conn = connect_db()
	c = conn.cursor()

	query = 'select * from users where username=?'
	c.execute(query, (username,))
	result = c.fetchone()

	if result : # already exists	
		return False
	
	avatar = ['munsiu', 'mommyhand', 'peach']
	shuffle(avatar)

	query = 'insert into users values (NULL, ?, ?, ?, ?)'
	c.execute(query, (username, password, avatar[0], comment,))
	conn.commit()
	
	c.close()
	return True


def get_post_list() :
	conn = connect_db()
	conn.row_factory = dict_factory

	c = conn.cursor()
	query = 'select * from board order by pid asc'
	c.execute(query)

	result = c.fetchall()	

	c.close()	
	return result


def get_post(pid) :
	conn = connect_db()

	conn.row_factory = dict_factory
	c = conn.cursor()

	query = 'select * from board where pid=?'
	c.execute(query, (pid,))
	result = c.fetchone()

	c.close()
	return result
	
	
def get_profile(uid) :
	conn = connect_db()
	
	conn.row_factory = dict_factory
	c = conn.cursor()

	query = 'select * from users where uid=?'
	c.execute(query, (uid,))
	result = c.fetchone()

	c.close()
	return result


def post_report(url) :
	correct_url = request.host_url + 'read'
	if url[:28] != correct_url :
		return False

	conn = connect_db()
	c = conn.cursor()
	
	query = 'insert into report values(NULL, ?)'
	c.execute(query, (url,))
	conn.commit()

	conn.close()
	return True
	

def insert_post(title, contents, username, avatar) :
	conn = connect_db()
	c = conn.cursor()

	query = 'insert into board values(NULL, ?, ?, ?, ?)'
	c.execute(query, (title, contents, username, avatar, ))
	conn.commit()

	conn.close()
	return True


def update_profile(uid, avatar, password) :
	conn = connect_db()
	c = conn.cursor()

	if avatar not in ['munsiu', 'mommyhand', 'peach'] :
		return False
	
	query = 'update users set avatar=?, password=? where uid=?'
	c.execute(query, (avatar, password, uid, ))
	conn.commit()

	conn.close()
	return True
