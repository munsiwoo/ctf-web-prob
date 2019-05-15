from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.common.exceptions import TimeoutException

import time, sqlite3
# admin robot selenium


def driver_init() :
	global driver

	chrome_options = Options()
	chrome_options.add_argument('--no-sandbox')
	chrome_options.add_argument('--window-size=1420,1080')
	chrome_options.add_argument('--headless')
	chrome_options.add_argument('--disable-gpu')

	driver = webdriver.Chrome(executable_path='/home/chromedriver', chrome_options=chrome_options)
	driver.implicitly_wait(3)
	driver.set_page_load_timeout(3)

def dict_factory(cursor, row):
	result = {}
	for idx, col in enumerate(cursor.description):
		result[col[0]] = row[idx]
	return result


def set_admin_password() :
	global conn
	c = conn.cursor()

	query = "update users set password=? where username='admin'"
	c.execute(query, (admin_password, ))
	conn.commit()

	return True

def get_report_url() :
	global conn
	conn.row_factory = dict_factory
	c = conn.cursor()

	query = 'select * from report order by rid limit 1'
	c.execute(query)
	fetch = c.fetchone()

	if fetch :
		rid = fetch['rid']
		c.execute('delete from report where rid={}'.format(rid))
		conn.commit()

		return fetch['url']
	
	return False


if __name__ == '__main__' :

	conn = sqlite3.connect('/home/prob/mydb.db')
	admin_password = 'b3a51a'
	driver_init()

	while True :
		try :
			report_url = get_report_url()
			if report_url :
				driver.get('http://httpd.shop:23906/profile')

				if driver.current_url == 'http://httpd.shop:23906/login' :
					set_admin_password()
					driver.execute_script("document.querySelector('[name=\"username\"]').value = 'admin'")
					driver.execute_script("document.querySelector('[name=\"password\"]').value = '{}'".format(admin_password))
					time.sleep(0.5)
					driver.execute_script("document.querySelector('[type=\"submit\"]').click()")
					time.sleep(1)

				driver.get(report_url)
				print('next', flush=True)
		
			else :
				time.sleep(1)

		except :
			driver.quit()
			driver = None
			driver_init()
