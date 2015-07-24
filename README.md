#Panchcofeed

##ExpressionEngine 2 Module for using Instagram API in templates

###Introduction 

**Panchcofeed** __brings data from Instagram's API into your templates__

Use it to display:

* [Your feed](#media_self)
* [Feeds you follow](#media_feed)
* [Items by hashtag](#media_hashtag)
* [Items you've liked](#media_self_liked)
* [Another user's feed ](#media_user)
* Profile information for yourself or another a user

###Tags

<article>
##media_self

Displays authenticated user's media items.

#####Parameters

|Parameter	|Default	|Required?	|
|-----------|---------|-----------|
|```media_count```|			1		| No				|
|```page_id```		|					| No				|

#####Singe Variables

|Variable|Description|
|---|-----------|
|```{error_message}```|API error message if any|
|```{application}```|Name of authorized application|
|```{endpoint}```|API endpoint URL used for current result set|
|```{next_page}```|Alias for API next_max_tag_id property|
|```{next_url}```|API endpoint URL for next set of results|
|```{metacode}```|API response code|
|```{error_type}```|API error type information|
|```{total_media```|Total media returned in current request to API|

####Variable Pairs

#####ig_user

Profile information for app owner

|Variable|Description|
|---|-----------|
|```{username}```|Username|
|```{bio}```|Bio string|
|```{website}```|Website|
|```{username}```|Username|
|```{profile_picture}```|URL to user's profile picture|
|```{full_name}```|User's full name|
|```{id}```|User's ID|

####media

Media items for current page.

|Variable|Description|
|---|-----------|
|```{link}```|link to media item on Instagram|
|```{filter}```|filter used|
|```{created_time}```|UNIX timestamp. Format this using EE's date variable formatting.|
|```{media_type}```|image or video|
|```{likes}```|"Like" count for a media item|
|```{media_tags}```|comma separated list of tags assigned to item|
|```{caption}```|caption|
|```{media_count}```|item number in the current set|
|```{id}```|media item id|
|```{ig_username}```|creator username|
|```{ig_user_profile_picture}```|URL to creator's profile picture|
|```{ig_user_full_name}```|creator's full name|
|```{ig_user_id}```|creator's user id|
|```{low_res_url}```|URL for low-res version of image|
|```{low_res_width}```|low-res width|
|```{low_res_height}```|low-res height|
|```{thumb_url}```|URL to the thumbnail version of the media item|
|```{thumb_width}```|thumbmail width|
|```{thumb_height}```|thumbnail height|
|```{standard_url}```|URL to standard version|
|```{standard_width}```|standard width|
|```{standard_height}```|standard height|
|```{video_low_bandwidth_url}```||
|```{video_low_bandwidth_width}```||
|```{video_low_bandwidth_height}```||
|```{video_low_resolution_url}```||
|```{video_low_resolution_width}```||
|```{video_low_resolution_height}```||
|```{video_standard_resolution_url}```||
|```{video_standard_resolution_width}```||
|```{video_standard_resolution_height}```||

#####Example:
```
{exp:panchcofeed:media_self media_count="9" page_id="{segment_3}"}
<h1>Profile</h1>
	{ig_user}
		<figure>
			<img src="{profile_picture}" alt="{full_name}" />
			<figcaption>{bio}<br>@{username} | <a href="{website}">{website}</a></figcaption>
		</figure>
	{/ig_user}
	
	<h2>My Feed</h2>
	{media}
		<figure>
			<a href="{link}"><img src="{low_res_url}" alt="{caption}" width="{low_res_width}" height="{low_res_height}" /></a>
			<figcaption>{caption}</figcaption>
		</figure>
	{/media}
	
		<p>
		{if next_page_id}
				<a href="{path="template-group/template}"}/{next_page_id}/">Next &raquo;</a>
		{/if}
		</p>
{/exp:panchcofeed:media_self}


```
</article>
<article>

##media_feed

Displays items from feeds authenticated user follows.

#####Parameters

|Parameter	|Default	|Required?	|
|-----------|---------|-----------|
|```media_count```|			1		| No				|
|```page_id```		|					| No				|

#####Singe Variables

|Variable|Description|
|---|-----------|
|```{error_message}```|API error message if any|
|```{application}```|Name of authorized application|
|```{endpoint}```|API endpoint URL used for current result set|
|```{next_page}```|Alias for API next_max_tag_id property|
|```{next_url}```|API endpoint URL for next set of results|
|```{metacode}```|API response code|
|```{error_type}```|API error type information|
|```{total_media```|Total media returned in current request to API|

####Variable Pairs

#####media

Media items for current page. Same as [media variable pairs](#media) in media_self.


#####Example:
```
{exp:panchcofeed:media_feed media_count="9" page_id="{segment_3}"}
	<h1>Items from other users feeds</h1>
	{media}
		<figure>
			<a href="{link}"><img src="{low_res_url}" alt="{caption}" width="{low_res_width}" height="{low_res_height}" /></a>
			<figcaption>{caption}</figcaption>
		</figure>
	{/media}
	
		<p>
		{if next_page_id}
				<a href="{path="template-group/template}"}/{next_page_id}/">Next &raquo;</a>
		{/if}
		</p>
{/exp:panchcofeed:media_feed}

```
</article>
<article>

##media_self_liked <a id="media_self_liked"></a>

Displays authenticated user's media items.

#####Parameters

|Parameter	|Default	|Required?	|
|-----------|---------|-----------|
|```media_count```|			1		| No				|
|```page_id```		|					| No				|

#####Singe Variables

|Variable|Description|
|---|-----------|
|```{error_message}```|API error message if any|
|```{application}```|Name of authorized application|
|```{endpoint}```|API endpoint URL used for current result set|
|```{next_page}```|Alias for API next_max_tag_id property|
|```{next_url}```|API endpoint URL for next set of results|
|```{metacode}```|API response code|
|```{error_type}```|API error type information|
|```{total_media```|Total media returned in current request to API|

####Variable Pairs

#####ig_user

Profile information for app owner

|Variable|Description|
|---|-----------|
|```{username}```|Username|
|```{bio}```|Bio string|
|```{website}```|Website|
|```{username}```|Username|
|```{profile_picture}```|URL to user's profile picture|
|```{full_name}```|User's full name|
|```{id}```|User's ID|

####media

Media items for current page. Same as [media variable pairs](#media) in media_self.

#####Example:
```
{exp:panchcofeed:media_self_liked media_count="9" page_id="{segment_3}"}
<h1>Profile</h1>
	{ig_user}
		<figure>
			<img src="{profile_picture}" alt="{full_name}" />
			<figcaption>{bio}<br>@{username} | <a href="{website}">{website}</a></figcaption>
		</figure>
	{/ig_user}
	
	<h2>My Feed</h2>
	{media}
		<figure>
			<a href="{link}"><img src="{low_res_url}" alt="{caption}" width="{low_res_width}" height="{low_res_height}" /></a>
			<figcaption>{caption}</figcaption>
		</figure>
	{/media}
		<p>
		{if next_page_id}
				<a href="{path="template-group/template}"}/{next_page_id}/">Next &raquo;</a>
		{/if}
		</p>
{/exp:panchcofeed:media_self_liked}
```
</article>
<article>
##media_hashtag

Displays media items by hashtag

#####Parameters

|Parameter	|Default	|Required?	|
|-----------|---------|-----------|
|```hashtag```|					|yes		|
|```media_count```|			1		| No				|
|```page_id```		|					| No				|

#####Singe Variables

|Variable|Description|
|---|-----------|
|```{hashtag}```|API error message if any|
|```{error_message}```|API error message if any|
|```{application}```|Name of authorized application|
|```{endpoint}```|API endpoint URL used for current result set|
|```{next_page}```|Alias for API next_max_tag_id property|
|```{next_url}```|API endpoint URL for next set of results|
|```{metacode}```|API response code|
|```{error_type}```|API error type information|
|```{total_media```|Total media returned in current request to API|

####Variable Pairs

####media

Media items for current page. Same as [media variable pairs](#media) in media_self.

#####Example:

```
{exp:panchcofeed:media_hashtag hashtag="stanleykubrick" media_count="9" page_id="{segment_3}"}
	<h1>{hashtag}</h1>
	{media}
		<figure>
			<a href="{link}"><img src="{low_res_url}" alt="{caption}" width="{low_res_width}" height="{low_res_height}" /></a>
			<figcaption>{caption}</figcaption>
		</figure>
	{/media}
	
		<p>
		{if next_page_id}
				<a href="{path="template-group/template}"}/{next_page_id}/">Next &raquo;</a>
		{/if}
		</p>
{/exp:panchcofeed:media_hashtag}
```
</article>

<article>
##media_user

Displays authenticated user's media items.

#####Parameters

|Parameter	|Default	|Required?	|
|-----------|---------|-----------|
|```ig_username```|					| Yes				|
|```media_count```|			1		| No				|
|```page_id```		|					| No				|

#####Singe Variables

|Variable|Description|
|---|-----------|
|```{error_message}```|API error message if any|
|```{application}```|Name of authorized application|
|```{endpoint}```|API endpoint URL used for current result set|
|```{next_page}```|Alias for API next_max_tag_id property|
|```{next_url}```|API endpoint URL for next set of results|
|```{metacode}```|API response code|
|```{error_type}```|API error type information|
|```{total_media```|Total media returned in current request to API|

####Variable Pairs

#####ig_user

Profile information for app owner

|Variable|Description|
|---|-----------|
|```{username}```|Username|
|```{bio}```|Bio string|
|```{website}```|Website|
|```{username}```|Username|
|```{profile_picture}```|URL to user's profile picture|
|```{full_name}```|User's full name|
|```{id}```|User's ID|

####media

Media items for current page. Same as [media variable pairs](#media) in media_self.

#####Example:
```
{exp:panchcofeed:media_user ig_username="vsco" media_count="9" page_id="{segment_3}"}
    {ig_user}
     <h1>{ig_username}</h1>
        <figure>
            <img src="{profile_picture}" alt="{full_name}" />
            <figcaption>@{username} | {full_name}</figcaption>
        </figure>
    {/ig_user}
    <h2>User Feed</h2>
    {media}
        <figure>
            <a href="{link}"><img src="{low_res_url}" alt="{caption}" width="{low_res_width}" height="{low_res_height}" /></a>
            <figcaption>{caption}</figcaption>
        </figure>
    {/media}
        <p>
        {if next_page_id}
                <a href="{path="template-group/template}"}/{next_page_id}/">Next &raquo;</a>
        {/if}
        </p>
{/exp:panchcofeed:media_user}
```
</article>