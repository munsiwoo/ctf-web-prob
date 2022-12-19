FROM python:3.7.9

RUN mkdir /[CENSORED]
RUN mkdir /memo

COPY ./src /[CENSORED]
COPY ./memo /memo

RUN chmod 777 -R /[CENSORED]
RUN chmod 777 -R /memo

COPY ./flag /[CENSORED]/flag

RUN pip install -r /[CENSORED]/requirements.txt

EXPOSE 8080
ENTRYPOINT ["python"]
CMD ["/[CENSORED]/app.py"]