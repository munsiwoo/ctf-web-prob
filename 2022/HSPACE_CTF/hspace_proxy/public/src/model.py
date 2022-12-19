import pymysql
import uuid

def connect_db():
    return pymysql.connect(
        host='admin.internal',
        user='root',
        passwd='hspace',
        db='hspace_proxy',
        charset='utf8mb4',
        cursorclass=pymysql.cursors.DictCursor)

def read_notice(notice_id):
    try:
        db = connect_db()
        cursor = db.cursor()
        cursor.execute(f"SELECT * FROM notice WHERE notice_id='{notice_id}'")
        fetch = cursor.fetchone()
        db.close()

        if fetch:
            return {"status": True, "data": fetch}

        return {"status": False}
    except:
        return {"status": False}

def list_notice():
    try:
        db = connect_db()
        cursor = db.cursor()
        cursor.execute(f"SELECT * FROM notice WHERE 1 LIMIT 3")
        rows = cursor.fetchall()
        retval = []

        for row in rows:
            retval.append(row)

        db.close()
        return retval
    except:
        return []