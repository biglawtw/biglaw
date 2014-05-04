#!/bin/bash
sudo update-rc.d -f hadoop-hdfs-datanode remove
sudo update-rc.d -f hadoop-hdfs-journalnode remove
sudo update-rc.d -f hadoop-hdfs-namenode remove
sudo update-rc.d -f hadoop-hdfs-secondarynamenode remove
sudo update-rc.d -f hadoop-hdfs-zkfc remove
sudo update-rc.d -f hadoop-httpfs remove
sudo update-rc.d -f hadoop-mapreduce-historyserver remove
sudo update-rc.d -f hadoop-yarn-nodemanager remove
sudo update-rc.d -f hadoop-yarn-proxyserver remove
sudo update-rc.d -f hadoop-yarn-resourcemanager remove

sudo update-rc.d -f hadoop-hdfs- remove
sudo update-rc.d -f hadoop-yarn-resourcemanager remove
sudo update-rc.d -f hadoop-yarn-resourcemanager remove

sudo update-rc.d -f hbase-master remove
sudo update-rc.d -f hbase-regionserver remove
sudo update-rc.d -f hbase-rest remove
sudo update-rc.d -f hbase-thrift remove

sudo update-rc.d -f hive-metastore remove
sudo update-rc.d -f hive-server remove
sudo update-rc.d -f hive-server2 remove
sudo update-rc.d -f hive-webhcat-server remove
sudo update-rc.d -f hive-hcatalog-server remove

sudo update-rc.d -f hue remove
sudo update-rc.d -f oozie remove

sudo update-rc.d -f zookeeper-server remove