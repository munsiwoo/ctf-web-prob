FROM python:3.7.9

RUN mkdir /service
COPY ./src /service
RUN chmod 777 -R /service

COPY ./flag /flag

RUN pip install -r /service/requirements.txt

EXPOSE 8080
ENTRYPOINT ["python"]
CMD ["/service/app.py"]