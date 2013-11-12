# This project has largely been abandonned. 
The need was too little and the competition was too great. 
Follow progress on [Rescue Case Management](https://github.com/MikeNGarrett/rcm)

This is based on a request from the Presidential Communications Development and Strategic Planning Office in coordination with the geeklist hackathon.
More information on the project can be found on [Geeklist](https://geekli.st/hackathon/52793a2660fb3f52d50001f8/project/527d25fd93f6ab665b000068)
Get in touch if you want to help [@MikeNGarrett](http://twitter.com/mikengarrett)

# The plan

## v1
Twitter search for RescuePH that hones down results to just what�s important.

__The goal is to provide a better twitter search tool that many people can use at the same time.__

## v2
Attempt to pull out information if possible in this format: "RescuePH < Name of person to rescue > < address, municipality>�
Attempt to pull out location data (if available).
Ability to mute accounts.

__The goal is to add features that will further enhance these results.__

## v3
Database integration (likely WordPress).

__The goal is to provide a platform that other services can use and the data can be worked with and displayed in different ways from one place.__

## v4
Tweets become nodes that have additional data associated, e.g. legitimate help request, urgency, location data, etc.
Views are built for different needs, e.g. list of recent requests available on a mobile device.

__The goal is to give volunteers the tools to organize tweets and push them out in different ways.__

v5
Build in more robust location data, e.g. what�s the most recent request closest to me?
Build in ability to notify that help is on the way (this may be problematic in more ways than one)

__The goal is to improve rescue response and status__



# Specifics
__v1__
Oauth shouldn't be need here. I'm setting up an app that will rate-limit requests and save them to a flat file.
The user-facing application will then use these flat files for any interaction.
I'm going to limit the search to rescueph (sans hashtag) and eliminate retweets.


