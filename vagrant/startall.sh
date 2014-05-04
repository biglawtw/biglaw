#!/bin/bash
sudo service hadoop-hdfs-datanode stop
sudo service hadoop-yarn-nodemanager stop
sudo service hadoop-hdfs-datanode start
sudo service hadoop-yarn-nodemanager start