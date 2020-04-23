import re
import urllib2
import pycurl
import sys
import time
import os
import hashlib

##########################################
#Tested on PYTHON 2.7.4
##########################################

def SaveURLToFile(url, filename):
    try:
        fileContent = urllib2.urlopen(url).read()
        with open(filename,"w") as f:
            f.write(fileContent)
        print "SUCCESS Save HTML:" + str(url)
    except KeyboardInterrupt:
        sys.exit(1)
    except urllib2.HTTPError, e:
        print "ERROR("+str(e.code)+") "+str(url)


def ParseSec(secUrl, secFolderPath):


    secUrlResponse = urllib2.urlopen(secUrl).read()

    patternRestResult = r'result-listing'
    restResult = re.split(patternRestResult, secUrlResponse)
    #print restResult[1]

    patternRestResultEnd = r'<div class="middle-section">'
    restResult = re.split(patternRestResultEnd, restResult[1])
    #print restResult[0]

    patternRestItem = r'<h6><a href="(.*)"'
    for restURL in re.findall(patternRestItem, restResult[0]):
        #print restURL

        restUrlResponse = urllib2.urlopen(restURL).read()

        global id

        restId = id
        restName = ""
        restAddr = ""
        restDistrict = ""
        restTel = ""
        restDesc = ""
        imgURL = ""
        skip = 0

        patternRestName = r'<span class="title">(.*)</span>'
        for restResult in re.findall(patternRestName, restUrlResponse):
            restName = restResult
            print "Restaurant Name:" + restName
            break
        if restName == "":
            skip = 1
        elif restName.encode('string_escape') != restName:
            skip = 1

        patternAddr = r'div class="head">Address</div><div class="content">(.*)<'
        patternAddr2 = '<'
        for restResult in re.findall(patternAddr, restUrlResponse):
            restResult = re.split(patternAddr2, restResult)
            restAddr = restResult[0]
            print "Address:" + restAddr
            break
        if restAddr == "":
            skip = 1
        elif restAddr.encode('string_escape') != restAddr:
            skip = 1

        patternDistrict = r'<div class="head">District</div>[\s]*<div class="content">[\s]*(.*)'
        patternDistrict2 = r'</a>'
        patternDistrict3 = r'>'
        for restResult in re.findall(patternDistrict, restUrlResponse):
            restResult = re.split(patternDistrict2, restResult)
            restResult = re.split(patternDistrict3, restResult[0])
            restDistrict = restResult[1]
            print "District:" + restDistrict
            break

        patternTel = r'<div class="head">Tel</div><div class="content">(.*)<'
        patternTel2 = r'<'
        for restResult in re.findall(patternTel, restUrlResponse):
             restResult = re.split(patternTel2, restResult)
             restTel = restResult[0]
             print "Tel:" + restTel
             break  
        if restTel == "":
            skip = 1
        restTel.replace(" ", "")

        restDistrict = restDistrict.replace(" ", "")

        if restDistrict == "Aberdeen" or restDistrict == "The Peak" or restDistrict == "TaiHang" or restDistrict == "SheungWan" or restDistrict == "Soho" or restDistrict == "Admiralty" or restDistrict == "ApLeiChau" or restDistrict == "CausewayBay" or restDistrict == "Central" or restDistrict == "HappyValley" or restDistrict == "LanKwaiFong" or restDistrict == "SaiWan" or restDistrict == "SaiWanHo" or restDistrict == "ShauKeiWan":
            districtId = '11'        #<option value="11">Central and Western</option>
        elif restDistrict == "KennedyTown" or restDistrict == "Mid-Levels" or restDistrict == "NorthPoint" or restDistrict == "QuarryBay" or restDistrict == "TaiKoo" or restDistrict == "TinHau":
            districtId = '12'        #<option value="12">Eastern</option>
        elif restDistrict == "Cyberport" or restDistrict == "RepulseBay" or restDistrict == "Stanley":
            districtId = '13'        #<option value="13">Southern</option>
        elif restDistrict == "WanChai":
            districtId = '14'        #<option value="14">Wan Chai</option>
        elif restDistrict == "KowloonTong" or restDistrict == "TaiKwokTsui":
            districtId = '21'        #<option value="21">Sham Shui Po</option>
        elif restDistrict == "HungHom" or restDistrict == "KowloonCity" or restDistrict == "ToKwaWan" or restDistrict == "WestKowloon":
            districtId = '22'        #<option value="22">Kowloon City</option>
        elif restDistrict == "KowloonBay" or restDistrict == "KwunTong":
            districtId = '23'        #<option value="23">Kwun Tong</option>
        elif restDistrict == "WongTaiSin":
            districtId = '24'        #<option value="24">Wong Tai Sin</option>
        elif restDistrict == "Jordan" or restDistrict == "KnutsfordTerrace" or restDistrict == "MongKok" or restDistrict == "Prince Edward" or restDistrict == "TsimShaTsui" or restDistrict == "YauMaTei":
            districtId = '25'        #<option value="25">Yau Tsim Mong</option>
        elif restDistrict == "Airport" or restDistrict == "LammaIsland" or restDistrict == "DiscoveryBay" or restDistrict == "LantauIsland" or restDistrict == "TungChung":
            districtId = '31'        #<option value="31">Islands</option>
        elif restDistrict == "KwaiFong" or restDistrict == "TsingYi":
            districtId = '32'        #<option value="32">Kwai Tsing</option>
        elif restDistrict == "":
            districtId = '33'        #<option value="33">North</option>
        elif restDistrict == "SaiKung" or restDistrict == "TseungKwanO":
            districtId = '34'        #<option value="34">Sai Kung</option>
        elif restDistrict == "ShaTin":
            districtId = '35'        #<option value="35">Sha Tin</option>
        elif restDistrict == "TaiPo":
            districtId = '36'        #<option value="36">Tai Po</option>
        elif restDistrict == "TsuenWan":
            districtId = '37'        #<option value="37">Tsuen Wan</option>
        elif restDistrict == "TuenMun":
            districtId = '38'        #<option value="38">Tuen Mun</option>
        elif restDistrict == "TinShuiWai" or restDistrict == "YuenLong":
            districtId = '39'        #<option value="39">Yuen Long</option>
        elif restDistrict == "Dongguan" or restDistrict == "Guangzhou" or restDistrict == "Macau" or restDistrict == "Shenzhen":
            skip = 1
        else:
            skip = 1

        if skip == 0:
            patternDesc = r'<div class="eating-review">[\s]*<p>(.*)<'
            patternDesc2 = r'<'
            for restResult in re.findall(patternDesc, restUrlResponse):
                restResult = re.split(patternDesc2, restResult)
                restDesc = restResult[0]
                print "Review:" + restDesc
                break  

            patternImg = r'<ul class="ad-thumb-list">[\s]*<li>[\s]*<a href="(.*)"'
            patternImg2 = r'"'
            for restResult in re.findall(patternImg, restUrlResponse):
                restResult = re.split(patternImg2, restResult)
                imgURL = "http://goodeating.scmp.com"+restResult[0]
                print "Img:" + imgURL
                break  
        
            if imgURL != "":
                directory = "img/rest/"+str(id)
                filepath = directory + "/" + "1.jpg"

                if not os.path.exists(directory):
                    os.makedirs(directory)
            
                print "Img filepath:" + filepath
                SaveURLToFile(imgURL, filepath)

                with open("sql.txt", "a") as sqlfile:
                    username = "restid"+ str(id)
                    password = "123456"
                    restEmail = "restid"+ str(id)+"@gmail.com"
            
                    h = hashlib.new('sha256')
                    h.update(username+password+'IamSalt2468!@#$%^&*()')
                    h.hexdigest()

                    restName = restName[0:49];
                    restAddr = restAddr[0:99];
                    restDesc = restDesc[0:999];
   
                    restName = restName.encode('string_escape')
                    restAddr = restAddr.encode('string_escape')
                    restDesc = restDesc.encode('string_escape')
       

                    sqlStr = "INSERT INTO restaurant (username, password, district, fullname, address, email, tel, description, rank, activate) VALUES (\'"+username+"\',\'"+h.hexdigest()+"\',\'"+districtId + "\',\'"+ restName+"\',\'"+restAddr+"\',\'"+restEmail+"\',\'"+restTel+"\',\'"+restDesc+"\',5, 1);\n";
                    sqlfile.write(sqlStr)


            id = id + 1

        #break
        #time.sleep(0.01)
        

try:
    os.remove("sql.txt")
except OSError:
    pass

id = 1

mainUrl1 = "http://goodeating.scmp.com/restaurants/search/apachesolr_search?keys=&location=&cuisine=&wine=&dish=&best_for=&features=&average_food_cost=&op=Search&filters=*&solrsort=&filters=*&solrsort="
ParseSec(mainUrl1, "rest/")

for page in range(1, 69):
    mainUrl = "http://goodeating.scmp.com/restaurants/search/apachesolr_search?page=" + str(page) + "&keys=&location=&cuisine=&wine=&dish=&best_for=&features=&average_food_cost=&op=Search&filters=*&solrsort="
    print mainUrl
    ParseSec(mainUrl, "rest/")
