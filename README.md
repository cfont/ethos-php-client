# Example PHP client scripts to demonstrate the ease of integrating to Ellucian Ethos Platform


This project is meant to work on files that demonstrate integrating with the Ellucian Ethos Platform. It was started with a branch called ["kiss"](https://github.com/cfont/ethos-php-client/tree/kiss) because I wanted to create some files to "keep it simple stupid" and, therefore, those files are, at the moment, single php scripts that do not currently rely upon any other framework or library. There is nothing special or fancy about those scripts as they have variables and parameters defined within them with very few error checking or useful functions or anything else like that. They display simple "pretty printed" JSON output, currently, with no array handling or table layout or anything that may seem end-user friendly.

**These are provided _as-is with absolutely no warranty or paid support_**. I have created these files and are sharing these files on my own without any direct Ellucian support so please do not expect ActionLine to necessarily help you with these particular files. Please feel free to create issues and generate pull requests and I will do my best to keep up with those be a good team player. I cannot stress enough how much I want to warn you away from using these files as some sort of production level application and instead want to make sure you understand these are meant to be examples so that you can understand how easy it is and how simple it can be to develop some integration with the Ellucian Ethos Platform. I wrote these on an airplane during a leg where I spent half the flight napping, a quarter of the flight talking to the guy seated next to me, and the rest of the time creating these simple examples. They work and that's really all they're meant to do.

One day I might should include some background information here about the Ellucian Ethos Platform in case you come looking at this project and aren't really sure what I'm talking about, but for now, I'm going to pretend you already know what that is and why it is important and explain a few things that may not be as readily apparent.

* These three scripts use the Ellucian Ethos Integration Proxy API.
  * That means this PHP app doesn't necessarily know where the data is coming from but that it is coming through Ethos.
  * These scripts ask Ethos for some information, Ethos proxies those requests to the authoritative source, and returns the result.
  * When I wrote these I was considering the authoritative source to be Banner which is only important for one of these files but can easily be adapted to Colleague or any other application authoritative for the information we want.
  * This is a REST API and uses JSON as the message format.
  * All three of these files are using the Persons Data Model to make it simple and show a few different use cases but also so it feels real and useful to someone needing to integrate to Ethos.
* All of these PHP scripts need a PHP server, obviously.
  * Because I used just a few straight up PHP functions there should be no problem running these examples on any current PHP implementation. Personally, I'm running Apache 2.4.18 and PHP 5.6.29. You should be good with any current XAMPP or WAMP or MAMP system.
  * The important bits to be sure are supported probably include JSON more than anything else.
* All of these scripts need to be modified slightly before you run them:
  * They each need an API key plugged into the variable `$apikey`
  * `ethosProxyGetPersonByGuid.php` needs to also have a Person GUID plugged into the variable `$ethosguid`
    * technically, this script could return any individual record from any data model if you swap out the variable `$ethosDataModel` and a valid related GUID in `$ethosguid`.
  * `ethosProxyGetPersonsByCredential.php` needs to also have a valid value in the `$credentialID` variable and a valid choice in the `$credentialType` variable. As delivered, "Banner ID" is what I chose and then plugged in my Banner ID into the `$credentialID` variable. You can leave it that way if you are ultimately going to hit Banner through Ethos or change it appropriately.
  * `ethosProxyGetPersonsByRole.php` doesn't really need anything else other than the `$apikey` but you can, of course, change the `$ethosRoleName` variable to something else like "Student". You can also change the `$ethosMaxReturn` variable to have more (or less) than 3 person records returned.

Again, **please be forewarned that these are workable examples not meant to be error proof or professionally reviewed.** I know there is a lot of refactoring that could happen to make it safer and better and more efficient and maybe one day I'll get there. But, this is a voluntary project I'm attempting and do not have a lot of time cycles to devote to making this any more awesome.

TODO List:

- [ ] Release this project using KISS branch and tag it 1.0.0
- [ ] Update project to use a config file so it seems a little more professional
- [ ] Update project to use search forms and provide a little more dynamo
- [ ] Update project to have a nice looking web site with menu and search forms and pretty layout of the results

