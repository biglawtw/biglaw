#!/bin/bash

# Start Zookeeper
sudo zookeeper-server stop
# Start Hadoop HDFS
sudo service hadoop-hdfs-namenode stop
sudo service hadoop-hdfs-datanode stop
sudo service hadoop-hdfs-journalnode stop
# Start Hadoop YARN
sudo service hadoop-yarn-resourcemanager stop
sudo service hadoop-yarn-nodemanager stop
# Start Hadoop MapReduce History Server
sudo service hadoop-mapreduce-historyserver stop
# Start HBase
sudo service hbase-master stop
sudo service hbase-regionserver stop
sudo service hbase-thrift stop
sudo service hbase-rest stop

sudo service hive-hcatalog-server stop
sudo service hive-server stop
# Start Oozie Server
sudo service oozie stop
# Start Hue
sudo service hue stop

################################ Start

# Start Zookeeper
sudo zookeeper-server start
# Start Hadoop HDFS
sudo service hadoop-hdfs-namenode start
sudo service hadoop-hdfs-datanode start
sudo service hadoop-hdfs-journalnode start
# Start Hadoop YARN
sudo service hadoop-yarn-resourcemanager start
sudo service hadoop-yarn-nodemanager start
# Start Hadoop MapReduce History Server
sudo service hadoop-mapreduce-historyserver start
# Start HBase
sudo service hbase-master start
sudo service hbase-regionserver start
sudo service hbase-thrift start
sudo service hbase-rest start

sudo service hive-hcatalog-server start
sudo service hive-server start
# Start Oozie Server
sudo service oozie start
# Start Hue
sudo service hue start
