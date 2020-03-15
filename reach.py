import sys
import urllib
from urllib.request import Request, urlopen
import re
import whois
import socket
from googlesearch import search
from bs4 import BeautifulSoup
from urllib.parse import urlparse

data = {}
followLinks = []
emails = ""
dnsInfo = ""
data['numbers'] = []
data['emails'] = []
data['twitters'] = []
data['users'] = []
data['pages'] = []

# NOTES TO SELF:
# FOLLOW LINKS ON CURRENT DOMAIN depth flag

site = sys.argv[1] # get the input site for scanning
parsed_uri = urlparse(site)
domain = '{uri.netloc}/'.format(uri=parsed_uri)
rootSite = domain.replace('www.', '')

site = site.replace('http://','') # remove protocols
site = site.replace('https://','')

try: #try to get additional info about the site
    whoisInfo = whois.whois(site)
    siteIP = socket.gethostbyname(site)
    dnsInfo = socket.gethostbyaddr(siteIP)
except:
    pass

def convert(set): 
        return sorted(set) 
    
def scrapePage(url):
    page = Request('http://'+url, headers={'User-Agent': 'Mozilla/5.0'})
    page = urlopen(page).read()
    soupPage = BeautifulSoup(page, features="html.parser")
    for script in soupPage(["script", "style"]):
        script.decompose() # remove script and style elements
    cleanPage = soupPage.getText()
    page = str(page)

    getnums = re.compile(r"(\+?\d?\s?\(?\d{3}\)?[-.\s]\d{3}[-.\s]?\d{4})") 
    numbers = getnums.findall(cleanPage)
    numbers2 = set(numbers)
    numbers3 = convert(numbers2)

    emails = re.findall('\w+[.|\w]\w+@\w+[.]\w+[.|\w+]\w+', page)
    emails2 = set(emails) 
    emails3 = convert(emails2)

    twitters = re.compile(r'@([A-Za-z0-9_]+)')
    twitters = twitters.findall(cleanPage)
    twitters = set(twitters)
    twitters = convert(twitters)
    twitters = ["@" + twitter for twitter in twitters]
    
    links = soupPage.find_all('a')

    global goodLinks
    goodLinks = []
    exts = ['.htm','.html','/']
    depth = 5
    currentDepth = 0
    for link in links:
        if(depth>currentDepth):
            goodLinks = [l for l in goodLinks if any(d in l for d in exts)]
            goodLinks.append(link.get('href'))
            currentDepth += 1
    data['numbers'] = numbers3
    data['emails'] = emails3
    data['twitters'] = twitters

    
    
    

scrapePage(site)

try: # try to scrape links
    for singleLink in goodLinks:
        try:
            scrapePage(rootSite + '/' + singleLink)
            data['pages'].append(singleLink)
        except:
            continue
except:
    pass

data['numbers'] = [a.strip() for a in data['numbers']] # remove spaces
data['numbers'] = convert(set(data['numbers'])) # remove dupes

data['emails'] = convert(set(data['emails'])) # remove dupes

#data['users'] = data['emails'][0].split["@"][0]

data['twitters'] = convert(set(data['twitters']))# remove dupes
data['site'] = site
#data['dns'] = dnsInfo

print(data)
#data['whois'] = whoisInfo
# Part 2: The deepening of charles' web; Look stuff up on Google!
#numbers[0] is the first number, look up the name of the site though
#for hit in search(site, stop=2):
#    page2 = urllib.request.urlopen(hit)
#    page2 = page2.read()
#    page2 = str(page2)



