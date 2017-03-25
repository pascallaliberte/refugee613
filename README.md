# Wordpress site based on the [Roots](http://roots.io) approach

## Install these first

### Requirements for Trellis

_Trellis sets up a virtual machine on your local machine with the right versions of PHP, an Nginx server, MySQL, and a bunch of mature defaults. It's also the recipe used to build prod and staging servers._

[Follow the instructions for installing Trellis](https://roots.io/trellis/docs/installing-trellis/) to install the following:

* Vagrant
* VirtualBox
* Ansible
* vagrant-bindfs >= 0.3.1 (Windows users may skip this if not using vagrant-winnfsd for folder sync)
* vagrant-hostmanager

### Requirements for Bedrock

Bedrock sets up Wordpress to have plugins and Wordpress itself managed as dependencies (using composer). It makes it easy for us to share the code to configure the site, to build the theme, etc.

* Composer - [Install](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)

## Structure

```
  trellis/ <= run `vagrant up` in here
  site/ <= Wordpress installs in here, plugins installed via composer, theme via Sage
```
