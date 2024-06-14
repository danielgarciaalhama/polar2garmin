# polar2garmin

---------UPDATE: DEPRECATED-----------

More or less 10 years ago I used to use a Garmin Etrex for MTB and Hiking, and a Polar watch GPS for running and other sports. I feel confortable using Garmin connect, so I imported all my trainnings from Polar to Garmin.

Several years ago Garmin changed their internal import functionality and I implemented this polar2garmin conversor created as a TCL standalone application. The problem was that on any kind of update I had to update it in all the computers where I used it. That was the reason why I have created this polar2garmin online conversor.

Some months ago, Garmin changed their code again, making the file conversion of polar2garmin not working anymore. Nowadays, I use my Polar device for tracking all my trainnings, even MTB and Hiking, so importing the trainnings from Polar to Garmin was just due I prefered Garmin connect. I kept this polar2garmin project just for fun, but I don't want to spend more time dealing with changes that does not depends on me. I haven't tried to guess what they have changed now, but it makes no sense continue investing more time on this topic having other services like strava that synchronizes both applications automatically (at least for now).

Also, since a while, I had in mind implementing a kind of "Garmin Connect" and "Polar flow" application for tracking the trainning and having some options that I do not have in these systems, so I prefer invest time on that. 


-------------END UPDATE---------------

If you try to import TCX trainnings exported from Polar Flow into Garmin Connect, Garmin will complain about them. 
I have implemented this polar2garmin simple website to transform the TCX trainnings from Polar format to Garmin format.

Also, loading trainnings that contains pauses into Garmin may generate some issues with the elevation and HR graphs (and probably other graphs). I have added the option "Remove pauses" that removes all the pauses into the trainning and makes Garmin showing the graphs with no issues. 

In resume, this is the http://www.polar2garmin.com source code. 

## Backup folder
When I implement a "big" change, I move the last working version to the backup folder. This backup folder is linked from the main page (in the last change comment), so in case that I add an issue in the last update, the user is able to back to the previous version and work with it (For example, it took me some weeks to realize that, depending on the screen configuration, the formulary was not shown when the last changes log was increased).
