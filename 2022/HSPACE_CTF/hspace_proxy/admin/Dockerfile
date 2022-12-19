FROM mariadb:latest

RUN apt update && apt install -y python3 python3-pip vim curl

RUN mkdir /app
COPY ./admin/src/ /app
RUN chmod 777 -R /app

RUN pip install -r /app/requirements.txt

COPY ./admin/flag.txt /flag.txt
RUN chmod 777 /flag.txt

EXPOSE 80