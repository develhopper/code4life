
# Code4life

This is my personal CMS im currently using for my own website

and you can use it if you like :)

  

## Installation

1. rename config.example.php to config.php

2. change configs as you desire in config.php

	**DEBUG** -> set true if you want to see warrning and errors otherwise set false

	**BASEURL** -> your website home url

	**BASEDIR** -> dont change this :), this variable must point to the root directory of project

	**UPLOAD_DIR** -> default upload directory

  

	**DB_DRIVER** -> set default PDO Diver if you're using mysql dont change it

	**DB_NAME** -> database name

	**DB_USER** -> database username

	**DB_PASSWORD** -> database password

  
	these 4 above keys used in QBuilder library see [QBuilder](https://github.com/develhopper/Qbuilder) documentation for more info


	**VIEWS_DIR** -> this is the directory of your views

	**CACHE_DIR** -> this is the directory used for storing compiled views

  

	these 2 above key used in Primal library see [Primal](https://github.com/develhopper/Qbuilder) documentation for more info

  

3. rename setup.example.php and change its content as your whishes and run below command:

		php setup.php

  

    this command will insert default values in database

    if you dont have shell access you can copy this file into public direcotry and run it by typing this url

  

	    https://yout-domain-address/public/setup.php