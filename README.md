Opauth-Asana
=============
[Opauth][1] strategy for Asana authentication.

Implemented based on http://developers.asana.com/documentation/#AsanaConnect using OAuth 2.0.

Opauth is a multi-provider authentication framework for PHP.

Getting started
----------------
1. Install Opauth-Asana:
   ```bash
   cd path_to_opauth/Strategy
   git clone https://github.com/marabooyankee/opauth-asana Asana
   ```

2. Create a Asana  project at https://app.asana.com/
  
   - Make sure to go to **Account settings** tab and **apps**.
   - Choose **Add New Application** 
   - Make sure that redirect URI is set to actual OAuth 2.0 callback URL, usually `http://path_to_opauth/asana/oauth2callback`

   
3. Configure Opauth-Asana strategy.

4. Direct user to `http://path_to_opauth/asana` to authenticate


Strategy configuration
----------------------

Required parameters:

```php
<?php
'Asana' => array(
	'client_id' => 'YOUR CLIENT ID',
	'client_secret' => 'YOUR CLIENT SECRET'
)
```



References
----------
- [Using Asana connect](http://developers.asana.com/documentation/#AsanaConnect)
License
---------
Opauth-Google is MIT Licensed  
Copyright © 2012 David Wambugu 

