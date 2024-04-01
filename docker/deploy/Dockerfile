FROM ubuntu:latest

RUN apt-get update && apt-get install -y \
    python3-pip \
    openssh-client

RUN pip3 install ansible==2.10.7 ansible-lint==6.0.2 jsondiff==2.0.0 passlib==1.7.4 PyYAML==6.0
