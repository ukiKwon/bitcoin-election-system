# KBK_ELECTION
# writer : uki408
# last update : 2017-08-12
#
################### dbTest ######################
#
- This dbTest Files is for user's Information. It pretends to be a CA, but actually almost a db.
It just mimics as this level. So Don't be mad. It will be better.

# db setting.md
- It's a kbk's db table composed of 6 columns.(name, register id, sex, age, region, vote_date, vote_chk)
We're planning to add a columns, bitcoin address maybe.

# db_config.php
- php configures db settings by this.

# db_register.php
- To insert into db, you can use this. It calls db_config.php and then give a series of join form.

# db_login.php
- Apps request user's identity from CA. In order to that, Apps communicate with a web managed by CA in sending POST form.
Then it'll check a query and reponse. If Apps receive a true, It goes next step voting candidates.
