# r&f

## Deployment
FTP-Deploy github action is setup and should deploy on push to main.

```bash
# Start
docker compose up --remove-orphans

 # Stop 
docker compose down

# Restart
docker compose restart
```
## todo

- done - add events page, add first event from the brochure in canva
  - add categories: Brunch Talks, Supper Clubs
  - add sorting
  - add calender
- create email list
  - page to sign up to email list
  - page to remove from email list

## requirements

- docker
- vs code sass watch extension
- acf pro (full version found on github for free)
- `composer require squizlabs/php_codesniffer --dev`
