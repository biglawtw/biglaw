#!/bin/bash
sudo apt-get -y update
sudo apt-get install -y vim
sudo apt-get install -y python-software-properties
sudo apt-add-repository -y ppa:webupd8team/java
sudo wget -O- http://archive.apache.org/dist/bigtop/bigtop-0.7.0/repos/GPG-KEY-bigtop | sudo apt-key add -
sudo wget -O /etc/apt/sources.list.d/bigtop.list http://www.apache.org/dist/bigtop/bigtop-0.7.0/repos/`lsb_release --codename --short`/bigtop.list
sudo apt-get -y update
