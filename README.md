# FAST Subdomain Finder

## Installation

### Step 1: Install PHP and Required Packages

Please run the following command to install PHP and its required packages:

```sh
sudo apt install php php-curl php-cli php-mysql php-xml php-mbstring php-zip php-gd php-json php-intl curl php-cli php-mbstring unzip
```

### Step 2 Clone the Repository

git clone https://github.com/store-manager-deluxe/FAST-Subdomain-Finder.git
cd FAST-Subdomain-Finder

### Step 3 Install COmposer

curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer


### Step 4 Install Project Dependencies

composer install


### Usage 

Prepare the subdomains.txt list. Add your own subdomains to the list or use your own personal list.
Inside of the public folder run
```
php core.php websitename.com subdomains.txt
```

### Example

```
php core.php youtube.com subdomains.txt
```

## Disclaimer

### Legal Notice

Subdomain scanning should only be performed on domains that you own or have explicit permission to test. Unauthorized scanning of subdomains on websites that you do not own or do not have permission to test may be illegal and could result in legal consequences. Always ensure you have the proper authorization before conducting any subdomain scanning activities. The authors of this project are not responsible for any misuse or damages caused by the use of this tool.


