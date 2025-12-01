import requests, time
from bs4 import BeautifulSoup

BASE = "http://localhost:8000"
FORM_URL = BASE + "/reviews/new"
SUBMIT_URL = BASE + "/app/utils/send_review.php"

s = requests.Session()

# 1) Fetch the form (this gives the token and form_time and sets PHPSESSID cookie)
r = s.get(FORM_URL)
soup = BeautifulSoup(r.text, "html.parser")
token = soup.find("input", {"name":"token"})["value"]
form_time = soup.find("input", {"name":"form_time"})["value"]
print("Fetched token:", token)
print("Fetched form_time:", form_time)
print("Session cookie:", s.cookies.get_dict())

# 2) Immediate submit (simulate a bot that posts ASAP) — expected: Spam detected (too fast)
resp_immediate = s.post(SUBMIT_URL, data={
    "token": token,
    "form_time": form_time,
    "fullname": "",          # honeypot left empty
    "name": "InstantBot",
    "email": "bot@local.test",
    "message": "instant message"
})
print("Immediate submit response:", resp_immediate.text)

# 3) Wait human-like time (e.g., 5 seconds) then submit — expected: success (or next checks)
time.sleep(5)
resp_human = s.post(SUBMIT_URL, data={
    "token": token,
    "form_time": form_time,
    "fullname": "",
    "rate": "5",
    "name": "RealHuman",
    "email": "real@example.com",
    "message": "hello after waiting"
})
print("After-wait submit response:", resp_human.text)

# 4) Submit while filling honeypot — expected: Spam detected
resp_honeypot = s.post(SUBMIT_URL, data={
    "token": token,
    "form_time": form_time,
    "fullname": "I am a bot",  # honeypot filled
    "name": "Spammy",
    "email": "spam@local.test",
    "message": "spammy"
})
print("Honeypot submit response:", resp_honeypot.text)
