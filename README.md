# TeamSpeak-HiddenPermsChecker

A simple PHP script to check if someone on your TeamSpeak server have "hidden permissions" assigned by administrators without your consent.

## "Hidden permissions"? Umm?
In TeamSpeak, you can assign permissions by groups and individually to clients / channels.
It means that some of your server administrators can assign permissions to users and essentially give them "superpowers" without you even knowing about it.
It also means that they can create "backdoor" with their another TeamSpeak identity.
And the worst thing is - you can't even see who have the hidden permissions, unless you check every single user that joined your server.

## Installation
Make sure you have PHP 5.4+ and composer installed.<br>
_But for the love of god, please use the latest PHP version avaiable (7.0+)_

- Clone / download the repository onto your server
```
git clone https://github.com/Wruczek/TeamSpeak-HiddenPermsChecker.git
```
- `cd` into the cloned folder
- Run `composer update`
- Edit the TeamSpeak server credentials inside of `hiddenpermschecker.php`

## Usage
Run `php hiddenpermschecker.php` inside of the cloned folder

Example output:
```
php hiddenpermschecker.php

Loading users, 100 at a time...
Detected 92 users, loading started
 > Loading part 0 / 92
Loaded 92 out of 92 users.
Scanning, this might take a few minutes...

Client UUID "8lmr4EP/IePET+JhX8pI+Mh3LAQ=" ("Wruczek") have hidden permissions

Finished. Found 1 users with hidden permissions.

Process finished with exit code 0
```

## What now?
To check what hidden permissions users have:
- Join your TeamSpeak server
- Go into `Permissions -> Client Permissions` (Command + F2 / CTRL + F2)
- Paste in the UUID of the clients with altered permissions.

You can also look for this user inside of your Server Log and find who assigned them permissions.
