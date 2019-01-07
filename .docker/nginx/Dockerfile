FROM nginx

MAINTAINER Priel Jann Escarilla <pjaescarilla@gmail.com>

###Set nginx configs###
COPY conf/nginx.conf /etc/nginx/nginx.conf
ADD sites-enabled/ /etc/nginx/sites-enabled/


###Download needed packages###
RUN apt-get update \
	&& apt-get install -y \
		vim \
		nano \
	&& rm -rf /var/lib/apt/lists/*

	
CMD ["nginx", "-g", "daemon off;"]

WORKDIR /app
