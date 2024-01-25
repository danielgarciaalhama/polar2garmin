# polar2garmin
If you try to import TCX trainnings exported from Polar Flow into Garmin Connect, Garmin will complain about them. 
I have implemented this polar2garmin simple website to transform the TCX trainnings from Polar format to Garmin format.

Also, loading trainnings that contains pauses into Garmin may generate some issues with the elevation and HR graphs (and probably other graphs). I have added the option "Remove pauses" that removes all the pauses into the trainning and makes Garmin showing the graphs with no issues. 

In resume, this is the http://www.polar2garmin.com source code. 

## Backup folder
When I implement a "big" change, I move the last working version to the backup folder. This backup folder is linked from the main page (in the last change comment), so in case that I add an issue in the last update, the user is able to back to the previous version and work with it (For example, it took me some weeks to realize that, depending on the screen configuration, the formulary was not shown when the last changes log was increased).
