FROM mysql:5.5

MAINTAINER Priel Jann Escarilla <pjaescarilla@gmail.com>

RUN apt-get update \
	&& apt-get install -y \
		vim \
		nano \
    && rm -rf /var/lib/apt/lists/*


WORKDIR /app
