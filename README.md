# INSTALLATION UBUNTU 14.04
- sudo apt-get update

- sudo apt-get install -y python-software-properties

- sudo add-apt-repository ppa:ondrej/php5
- sudo apt-add-repository ppa:phalcon/stable

- sudo apt-get update

- sudo apt-get install -y php5 php5-dev apache2 libapache2-mod-php5 mysql-server php5-mysql php5-phalcon

- sudo a2enmod rewrite

- sudo nano /etc/apache2/sites-available/000-default.conf
    > set DocumentRoot to : /var/www
- sudo nano /etc/apache2/apache2.conf
    > Set AllowOverride All to directories : / & /var/www

- sudo service apache2 restart

- cd /var/
- sudo rm -rf www/
- sudo mkdir www/
- sudo chmod -R 777 www/

# Important Informations 

> CSRF TOKEN MUST BE GENERATED IN INDEX ACTION OF CURRENT CONTROLLER , IF IT'S IN CONTROLLER BASE IT WILL GENERATE A NEW TOKEN MAKING THE REQUEST INVALID

# BACKEND

- Articles Controller (ArtigosController) : working with modals , on edit or remove category will promt a modal via the RequestController creating a FORM with actions of post to ArtigosController to update.