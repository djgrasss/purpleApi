purpleApi
=========

Purple API is an exercise project I made to train myself on the SOA approach in the Web development world 
and how we can implement this in a maintainable way.

It's composed in 2 separate parts.

Server part
===========
An SOA oriented API Backend :
- Api structure made from scratch and partially inspired from the WebApi approach of ASP.NET MVC
- An homemade IOC container to allow easy coupling of the code modules and code reuse from the different classes
- An homemade persistence layer based on text files. We could use, instead of this, an external tool like memcached.

Client Part
===========
A Single Page Application based on :
- KnockoutJS for the MVVM models binding and sync
- IOC by using requireJs
- A separation of concern by using "namespaces" for each type of data treated
- Twitter bootstrap / JqueryUI for the UI
- Sammy.js for the SPA page navigation

