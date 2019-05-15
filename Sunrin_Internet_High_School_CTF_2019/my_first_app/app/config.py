import sqlite3

def connect_db() :
	conn = sqlite3.connect('mydb.db')
	return conn

def dict_factory(cursor, row):
	result = {}
	for idx, col in enumerate(cursor.description):
		result[col[0]] = row[idx]
	return result
