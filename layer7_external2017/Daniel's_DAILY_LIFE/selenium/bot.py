#-*- coding:utf-8 -*-
from selenium import webdriver
from time import sleep

admin_id = "admin"
admin_pw = "iopqwe123qwe@"
login_page = "http://ctf.layer7.kr:6002/bbcode/login.php"
read_page = "http://ctf.layer7.kr:6002/bbcode/admin/admin_read.php"

driver = webdriver.PhantomJS()
driver.implicitly_wait(3)

driver.get(login_page)
driver.find_element_by_name("username").send_keys(admin_id)
driver.find_element_by_name("password").send_keys(admin_pw)
submit_button = driver.find_elements_by_xpath("//input[@value='Submit']")[0]
submit_button.click()

while(1) :
	driver.get(read_page)
	sleep(2)