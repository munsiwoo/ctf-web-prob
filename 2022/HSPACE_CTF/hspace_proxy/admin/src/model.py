import pymysql
import uuid

def connect_db():
    return pymysql.connect(
        host='localhost',
        user='root',
        passwd='hspace',
        db='hspace_proxy',
        charset='utf8mb4',
        cursorclass=pymysql.cursors.DictCursor)

def check_token(token):
    db = connect_db()
    cursor = db.cursor()
    cursor.execute("SELECT token FROM admin LIMIT 1")
    fetch = cursor.fetchone()
    db.close()

    if fetch['token'] != token:
        return False

    return True

def login(username, password):
    try:
        db = connect_db()
        cursor = db.cursor()
        cursor.execute(f"SELECT * FROM admin WHERE username='{username}' AND password='{password}'")
        fetch = cursor.fetchone()
        db.close()

        if fetch:
            return {"status": True, "token": fetch['token']}

        return {"status": False}
    except:
        return {"status": False}

def write_notice(title, contents):
    try:
        db = connect_db()
        cursor = db.cursor()
        notice_id = str(uuid.uuid4())
        cursor.execute(f"INSERT INTO notice VALUE ('{notice_id}', '{title}', '{contents}')")
        db.commit()
        db.close()
            
        return {"status": True, "notice_id": notice_id}
    except:
        return {"status": False}