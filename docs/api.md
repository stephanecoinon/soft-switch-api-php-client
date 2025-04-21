# Soft-Switch API reference

Source: `{{BASE_URL}}?reqtype=HELP&tenant={{USERNAME}}&key={{API_KEY}}`

<pre>
Parameters:
  reqtype - Request type
  tenant  - Sometime optional if the Admin API key is used, otherwise provide the tenant code
  key     - Use the Admin API key or the Tenant API key
  format  - Optional, can be <i>json</i> or <i>plain</i>
  callback - Optional, for cross domain script, the function to use as callback (requires format json)
  language - Optional, when supported, provide a translation of the data
  cache    - set to no to not use the cache for SQL and AMI interface

Request type (reqtype):
COUNTPEERS - Return the number of peers on each node and the total
           - Additional parameters:
             tenant - optional if using the Admin API key,
                      returns only peers from the selected tenant
COUNTCHANNELS - Return the number of channels (total or on each node)
           - Additional parameters:
             nodename - optional, if you want only that node counted
             tenant - optional if using the Admin API key,
                      returns only channels from the selected tenant
CHANSIPPEERS - Show the peers registered on all nodes of the network
           - Additional parameters:
             tenant - optional if using the Admin API key,
                      returns only peers from the selected tenant
BLFS       - Get the BLF status, peers and flows
             tenant - the tenant to report
DND        - Manage the DND for an extension
           - Additional parameters:
             number - number to set the DND for
             action - select the action type:
                    get - get the DND state of extension
                    set - set the DND state of extension
                        value - DND status to set, 'on' or empty to disable
FLOWS      - Show the flow status
             tenant - the tenant to report
CHANNEL    - Show the channel details for the selected tenant
             Additional parameters: 
             channel - the channel to show info
CHANNELS   - Show the channels for the selected tenant
             Additional parameters: 
             nodename - optional, if you want only that node counted
             tenant - optional, show channels for the tenant
GETCURRENTCALLS - Show current running calls
             Additional parameters: 
             phone - the extension number to get
             tenant - the tenant of the extension
             multiple - optional, set to yes if you want to receive multiple channels
PHONEBOOKS - List phone book names:
             Additional parameters: 
             tenant - select the tenant
             subreqtype - select the sub request type:
                    list - list all the phonebooks
PHONEBOOK  - Manage phone books:
             Additional parameters: 
             tenant - select the tenant
             phonebook - select the phone book by name
             subreqtype - select the sub request type:
                    query - perform a query
                        field - name of the field
                        value - value to search for (use % for partial searches)
                    add - add a record
                        values - a json encoded associative array with name of the fields and values, you can use also an array of associative arrays
                    delete - delete a record
                        peid - peid value returned by search
                    cleanall - delete all the records in the phonebook
                    getall   - get all the records in the phonebook
INFO       - Get info about system
             Additional parameters: 
             info - type of info: 
                    queues (list of queues) 
                    queue (info about the queue based on id) 
                    queuecallers (info about the callers waiting in the queue based on id)
                    agents (info about the agents in all or selected queue) 
                    agentsconnected (info about the agents in all or selected queue, but only if currently connected) 
                    agentsdelay (info about the agents delay in answering in all or selected queue) 
                    outdialed (info about the calls dialed out by extensions) 
                    simpledialed (info about the calls dialed out and in by extensions using the simple call history) 
                    call (info about the call originated with the api using the returned id or unique id) 
                    fax (info about the fax originated with the api using the returned id or unique id) 
                    inforecording (get the metadata associated to the recording for the call, you can use the unique id or the originated id)
                    recording (get the recording for the call, you can use the unique id or the originated id)
                    recordings (get the list of recording for a tenant, you can use the start and end parameters)
                    transcript (get the transcript for a recording, you can use the unique id)
                    playrecording (play the recording for the call, you can use the unique id or the originated id)
                    voicemail (get the voicemail message for the call, you can use the id of the voicemail_messages table)
                    voicemailtranscript (get the voicemail message transcript for the call, you can use the id of the voicemail_messages table)
                    dids (list of dids)
                    pincodes (list of pincodes)
                    sms (list of sms sent and received)
                    extensions (list of extensions, including state)
                    extstate (get the state of the extension, including the number speaking with)
                    regstate (get the registraiton state of the extension)
                    config (get info about configured tenant)
                    cdrs (get the list of calls, you can use wildcard % for the tenant code)
                         Further parameters available: id, uniqueid, linkedid, src, firstdst, direction, phone, format(csv,xml), template
                         Phone is a special parameter filtering for whoanswered, calleridnum and dialednum
                         You can filter for multiple parameter values using a comma separator
                    simplecdrs (get the list of calls using the simple call history source, you can use wildcard % for the tenant code)
                         Further parameters available: id,uniqueid,calleridnum,calleridname,disposition,direction,whoanswered,phone,format(csv,json)
                         Phone is a special parameter filtering for whoanswered, calleridnum and dialednum
                         You can filter for multiple parameter values using a comma separator
                    queuelogs (get the list of calls processed by queue)
                    ivrlogs (get the list of actions on the ivr)
                    balance (get the credit available)
                    flow (get the flow status)
                    variable (get the custom variable value)
             id - optional, id of object requested 
             queue - optional, id of queue requested for agents info or queue logs 
             start - optional, start date/time of the sms, cdrs or queue logs or balance returned 
             end   - optional, end date/time of the sms, cdrs or queue logs or balance returned 
AGENT        - Manipulate queue agent status and get informations
               Additional parameters: 
               tenant - name of the tenant
               extension - agent username or extension number
               queue - queue id (alternatively use queuenumber, use ALL for all the queues)
               queuenumber - queue number (alternatively use queue)
               action - action to perform (pause, unpause, listqueues, del)
               pausereason - optional, pause reason to set
               refresh - optional, set to no to avoid queue refresh
ATXTRANSFER  - Attend transfer a channel
               Additional parameters: 
               tenant - optional, tenant for the channel to transfer
               channel - channel to transfer
               dest - number to transfer the channel to
CAMPAIGN     - Manage call campaign
               Additional parameters: 
               campaign - id for campaign, you need to get it on the URL for the editing campaign
               tenant - optional, tenant for the campaign
               action - action can be start, stop, pause, resume, addnumber, delnumber, listnumbers, cleannumbers. Start and stop can affect only on-demand campaign
               number - optional, the number to call when using the addnumber action
               numberdescription - option, the number description when using the addnumber action
DIAL         - Dial a number and connect to another number
               Additional parameters: 
               tenant - tenant where to place the call
               source|?exten - first number to dial - use ACCOUNT to use the number associated with the account chosen
               dest|phone - number to connect
               var - variables to set, will be prefixed tenant code
               account - account name to simulate the call from - use SOURCE to use the account associated with the source number choosen
               dialtimeout - dial timeout in seconds
               timeout - max duration for the call in seconds
               sourceclid - use this clid for dialing source number
               destclid - use this clid for dialing dest number
               recording - set the recording for the call, use: yes, no, yeschange or nochange)
               server - you can ask to use a specific server to dial
               autoanswer - you can require the source phone to auto answer setting this parameter to 'yes'
               nofollow - you can require the source phone to not follow on additional destinations setting this parameter to 'yes'
FAX          - Manage Faxes
               Additional parameters: 
               action - Available actions
                        send - send a fax
               source_number - The number to send the fax from
               dest_number - The number to send the fax to
               quality - Optional fax quality ('204x98', '204x196' or '204x392'
               pagesize - Optional page size ('a4', 'letter' or 'legal'
               rotate - Optional rotation (empty for automatic, 'E' or 'W' for East/West rotation or 'no' for no rotation)
               deleteaftersend - Optional, set to 'on' to delete the fax once sent
               schedule - Optional, set the date/time for sending the fax
               statusemail - Optional, set the email to send status updates
               filename - The PDF for the fax to be sent
HANGUP       - Hangup a channel
               Additional parameters: 
               tenant - optional, tenant for the channel to hangup
               channel - channel to hangup
               extension - optional, extension to hangup, like 103-DEVEL
               number - optional, extension number to hangup, like 103
               source - optional, extension number to hangup, like 103
               fast - optional, set to yes to use a new location algorithm for the channel, much faster
MEDIAFILE    - Manage media files
               Additional parameters: 
               action - Available actions
                        getaudio - Download the audio
               tenant - tenant to get audio from
               objectid - media id
QUEUE        - Manage queue agents
               tenant - tenant for the queue
               number - queue number (alternatively use id). You can use NONE when applicable
               id     - queue id (alternatively use number)
               uniqid - optional, use this as unique id for the log record
               extension - optional, number or extension username to add or delete. You can use NONE when applicable
               action - Available actions
                        list     - List queue agents
                        fulllist - Show all agents info
                        add      - add an agent to the queue
                        del      - del an agent from the queue
                        clean    - del all agents from the queue
                        log      - create a custom log entry in the queue_log table
               order  - optional, numeric order of the agent in the agent list
               type   - optional, agent type (NF - Not Following, AD - Additional Destinations, NFR - Not Following with Ring, ADR - Additional Destinations with Ring
                                              EA - External Agent, EANM - External no monitoring, EAC - External with confirm, EANMC - External no monitoring with confirm
                                              CU - Custom destination, CUNM - Custom destination no monitoring, CC -Custom destination with confirm
                                              CUFF - Custom destination with full features
               event  - optional, custom log event to generate
               paused - optional, set to 0/1 to add the agent in paused state
               penalty - optional, set to the penalty to assign to the added agent
               data[1-5] - data1... data5 to populate custom log event (optional)
QUEUERESET   - Reset statistics for a queue
               Additional parameters: 
               tenant - optional, tenant for the queue
               queueid - queue id to reset
REBOOT       - Reboot one or multiple peers
               Additional parameters: 
               peer - peername to reboot or ALL for all peers
               tenant - optional, tenant for the peer to reboot
RESPONSEPATH - Get info from response path object
               tenant - tenant for the response path to query
               id - response path id
               rrid - response path response id or response path uniqueid
               filter - optional available filters
                        queue - Apply filter on queue id
                        answer - Apply filter on answer extension
                        uniqueid - Apply filter on unique id
               filterdata - optional, the value to filter on
               action - Available actions
                        list - List all available responses
                        getid - Get the details from the specified response path rrid
                        getlast - Get the details from the latest one
SETFLOW      - Set Flow status or variable
               Additional parameters: 
               number - flow number
               state - optional state (INUSE, NOT_INUSE, UNAVAILABLE, RINGING)
               value - optional value
               tenant - optional, tenant for the flow to set
SMS          - Send an SMS
               Additional parameters: 
               tenant - tenant where to place the call
               source - the source number
               dest - number to send the SMS to
               destclid - use this clid for sending the SMS to the dest number
               server - you can ask to use a specific server to dial
               message - the message to be sent
TRANSFER     - Transfer a channel
               Additional parameters: 
               tenant - optional, tenant for the channel to transfer
               channel - channel to transfer
               extrachannel - extra channel to transfer (optional)
               dest - number to transfer the channel to
UNREGISTER   - Unregister one or multiple peers
               Additional parameters: 
               peer - peername to unregister or ALL for all peers
               tenant - optional, tenant for the peer to unregister
USERGROUP    - Manage the user groups
               usergroupid - for selecting the usergroup to list tenants, users or add/delete
               tenantid - for selecting the tenant to add/delete
               userid - for selecting the user to add/delete
               inerith - optional, 'on' for setting inerith for a user
               action - Available actions
                        list        - list the available user groups
                        listtenants - list the tenants for a user group
                        listusers   - list the users for a user group
                        addtenant   - add the tenantid to the user group
                        deltenant   - delete the tenantid from the user group
                        adduser     - add the userid to the user group
                        deluser     - delete the userid from the user group
VIRTUALEXT   - Manage virtual extensions
               tenant - tenant for the virtual extension
               number - virtual extension number
               extension - optional, number or extension username to add or delete
               action - Available actions
                        list - List extensions joined under the virtual extension
                        add   - add an extension to the virtual extension
                        del   - del an extension from the virtual extension
                        clean - del all extensions from the virtual extension
VOICEMAIL    - Get info about voicemails
               tenant - optional, tenant for the voicemails
               mailbox - optional, show info about a specific mailbox
               msgid - optional, message id
               action - Available actions
                        list - List available voicemails
                        messages - List messages for mailbox specified
                        message - Get the message binary for msgid specified
                        delete - Delete the message for msgid specified
                        markread - Mark the message as read
                        markunread - Mark the message as not readed
SETTING      - Get or Set tenant settings
               action - GET/SET
               tenant - for selecting the tenant
               name - the setting internal name
               value - optional, when setting the value
COUNTCALLS   - Count the number of calls running
LICENSEDAYS  - Get the day before license expiration
BALANCE     - Alter tenant balance
              Additional parameters: 
              tenant - tenant to change the balance
              amount - sum to add to balance, use a negative sum to subtract
              description - description
CDR         - Get of update CDR data
              Additional parameters:
              action - action to perform
                       update - update a field
                       get - get a field
              field - field to get or update, you can use 'userfield'
              uniqueid - uniqueid of the call
              value - value to add to the end of the field
MANAGEDB    - Manage data in database
              Additional parameters: 
              object - object name to manage ('tenant', 'custom', 'customtype', 'phone', 'mediafile', 'huntlist', 'extension', 'provider', 'did',
                                              'musiconhold', 'user', 'conference','routingprofile', 'route', 'destination', 'ivr',
                                              'calleridblacklist','condition', 'sipregs', 'phonephonebook' are currently the only options)
              routingprofileid - optional, when getting the list of the routes, returns only the one for the specified routing profile
              typesrc - optional, used for selecting the type of object in destination
              typeidsrc - optional, used for selecting the object id in destination
              action - action to perform
                       for tenant object type, you can use 'list' with a filter parameter, 'get', 'add', 'update' and 'delete'
                       for mediafile object type, you can use 'list', 'get', 'add', 'update', 'delete', 'updatebinary' and 'getbinary'
                       for huntlist object type, you can use 'list', 'get', 'add', 'update', 'delete', 'getextensions' and 'setextensions'
                       for extension object type, you can use 'list', 'get', 'add', 'update' and 'delete'
                       for user object type, you can use 'list', 'get', 'add', 'update', 'delete', 'listtenants', 'addtenant' and 'deltenant'
                       for conference object type, you can use 'list', 'get', 'add', 'update' and 'delete'
                       for provider object type, you can use 'list', 'get', 'add', 'update', 'delete' and 'listroutes'
                       for route object type, you can use 'list', 'get', 'add', 'update' and 'delete'
                       for did object type, you can use 'list', 'get', 'add', 'update' and 'delete'
                       for ivr object type, you can use 'list', 'get', 'add', 'getdestinations', 'update' and 'delete'
                       for custom object type, you can use 'list', 'get', 'add', 'update' and 'delete'
                       for musiconhold object type, you can use 'list' ,'get', 'add', 'update' and 'delete'
                       for voicemail object type, you can use 'list', 'listmessages', 'getmessagerecording', 'putmessagerecording','get', 'add', 'update' and 'delete'
                       for destination object type, you can use 'list' and 'replace'
                       for calleridblacklist object type, you can use 'list', 'get', 'add', 'update' and 'delete'
                       for condition object type, you can use 'list', 'get', 'add', 'getdestinations', 'getextendedinfos', 'replaceextendedinfos', 'update' and 'delete'
                       for sipregs object type you can use 'list'
                       for phones object type you can use 'list', 'get', 'update' and 'add'
                       for phone phonebooks object type you can use 'list', 'get', 'update', 'add' and 'delete'
                       for queue object type, you can use 'list', 'get', 'add', 'update' and 'delete'
              objectid - object id to update
              tenantid - options, to add/remove tenants from authorized user
              jsondata - jsondata for action - check manual for more info
CHECKAUTH   - Check authentication, return info about the privileges for the user
            - username - username
            - password - password
AUTHTOKEN   - Manage authentication tokens for users. The token can be used instead of the password for the authentication
              action - action to perform
                       generate - generate a token for the user
                       reset - delete the token for the user
              validity - ONCE for single use
                         valid date/time
              user - username to operate with
GETWEBRTCAUTH - Authenticate an extension user and return the details for a WebRTC connection
              - username - username
              - password - password
</pre>