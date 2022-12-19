FROM python:3.10

RUN mkdir /app
COPY ./public/src /app
RUN chmod 777 -R /app

RUN pip install -r /app/requirements.txt

EXPOSE 8080
ENTRYPOINT ["python"]

CMD ["/app/app.py"]