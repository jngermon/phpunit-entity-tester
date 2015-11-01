docker-compose.env: docker-compose.env.dist
	@ cp docker-compose.env.dist docker-compose.env
	@ sed --in-place "s/{your_unix_local_username}/$(shell whoami)/" docker-compose.env
	@ sed --in-place "s/{your_unix_local_uid}/$(shell id --user)/" docker-compose.env

