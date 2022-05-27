from selenium import webdriver
import requests
import time

while True:
    r = requests.get("http://sunrin.kiminfo.kr:18777/onlybot.php")
    if r.text != '0':
        driver = webdriver.Chrome('./chromedriver.exe')
        driver.implicitly_wait(3)
        driver.set_page_load_timeout(3)
        
        driver.get("http://sunrin.kiminfo.kr:18777/")
        driver.add_cookie({"name": "FLAG", "value": "SUNRIN{rp0rpOrpOrpOzZrp0rprOprOp}"})
        try:
            driver.get(r.text)
            print(r.text)
            time.sleep(3)
            driver.quit()
        except:
            driver.quit()
    else:
        time.sleep(5)
    
    print("It's working. (RPO)", flush=True)
        
        
