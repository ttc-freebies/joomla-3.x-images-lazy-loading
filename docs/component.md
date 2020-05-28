# Update the existing database records

!> BACKUP your Database. The component will alter the contents of introtext and fulltext columns. Be safe!

- Download the [component addlazyloading v1.0.0-beta](https://ttc-freebies.github.io/com_addlazyloading/com_addlazyloading_1.0.0.zip ':ignore') and install it through the usual process.
- The component by default will **only** search and replace image tags < img /> in the the intotext and fulltext columns of the content table
- Adjust the steps. Meaning how many records you want the component to process per cycle. The defualt is one bu you can raise it to speed up the process.
- Hit the button and go grab a coffee, launch or beer. This might take a bit depending of the size of your database.
- There is a counter that updates on each cycle, so you can follow the process, if that's what you want
- Once the process has finished (there will be a message) you should check that the db is updated with the attribute `loading="lazy"` added to each image in all the columns
- UNISTALL the component, it's job is done!


## Update any table/column
As you probably noticed there is an Options button in the components toolbar. You can use it to apply the attribute to **any** table/column.
To do so you need to know the **identifier column** for the table, usually `id` or `itemId`. If in question please consult the developer of the extension
Specify the table name
Specify any columns that usually are wired to your WYSIWYG.

!> Please be extra careful with these options!!!

Once you've saved these options adjust once again the steps per cycle and hit the green button.

Enjoy a faster website!
