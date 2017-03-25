# Wordpress site based on the Roots approach

## Install these first

Follow the instructions in the [Installing Trellis documentation](https://roots.io/trellis/docs/installing-trellis/)

* Vagrant
* VirtualBox
* Ansible
* vagrant-bindfs >= 0.3.1 (Windows users may skip this if not using vagrant-winnfsd for folder sync)
* vagrant-hostmanager

## Structure

```
  trellis/ <= run `vagrant up` in here
  site/ <= Wordpress installs in here, plugins installed via composer, theme via Sage
```
