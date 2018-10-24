{if is_set($view_parameters.error)}
    <div class="message-warning alert alert-danger">
        <p>{$view_parameters.error|wash()}</p>
    </div>
{/if}
{if $is_detail}    
    <h1>{'Archived items by %1 on %2'|i18n( 'extension/ocopendata_archiver',, array($user_name, $timestamp|l10n( 'shortdatetime' )) )}</h1>
    <div class="block">            
    {if not( $list_count )}
        <p><strong>{"No archived items"|i18n( 'extension/ocopendata_archiver' )}</strong></p>
    {else}
        <table class="list" cellspacing="0">
            <thead>
                <tr>
                    <th>{"ID"|i18n( 'extension/ocopendata_archiver' )}</th>
                    <th>{"Class"|i18n( 'extension/ocopendata_archiver' )}</th>
                    <th>{"Url Alias"|i18n( 'extension/ocopendata_archiver' )}</th>                    
                    <th width="1"></th>
                    <th width="1"></th>
                </tr>
            </thead>

            <tbody>
                {foreach $list as $item sequence array( 'bglight', 'bgdark' ) as $trClass}
                <tr class="{$trClass}" id="item-{$item.id}">
                    <td>{$item.object_id}</td>
                    <td>{$item.class_identifier}</td>
                    <td>{$item.url_alias_list_decoded|implode('<br />')}</td>                    
                    <td><a class="btn btn-xs btn-info" href="{concat('archiver/view/',$item.id)|ezurl(no)}">{"View"|i18n( 'extension/ocopendata_archiver' )}</a></td>
                    <td><a class="btn btn-xs btn-danger" href="{concat('archiver/unarchive/',$item.id)|ezurl(no)}">{"Unarchive"|i18n( 'extension/ocopendata_archiver' )}</a></td>                    
                </tr>
                {/foreach}
            </tbody>
        </table>
        <p>&nbsp;</p>
    {/if}
    </div>
{else}
    <h1>{'Archive list'|i18n( 'extension/ocopendata_archiver' )}</h1>
    <div class="block">
        <p>
            <a class="btn btn-xs btn-success" href={'/archiver/archive'|ezurl}>{'Archive an item'|i18n( 'extension/ocopendata_archiver' )}</a>
            <a class="btn btn-xs btn-info" href={'/archiver/search'|ezurl}>{'Search an archived item'|i18n( 'extension/ocopendata_archiver' )}</a>
        </p>
    {if not( $list_count )}
        <p><strong>{"No archived items"|i18n( 'extension/ocopendata_archiver' )}</strong></p>
    {else}
        <table class="list" cellspacing="0">
            <thead>
                <tr>
                    <th>{"Type"|i18n( 'extension/ocopendata_archiver' )}</th>
                    <th>{"Date"|i18n( 'extension/ocopendata_archiver' )}</th>
                    <th>{"User"|i18n( 'extension/ocopendata_archiver' )}</th>                
                    <th>{"Status"|i18n( 'extension/ocopendata_archiver' )}</th>
                    <th>{"Archived objects"|i18n( 'extension/ocopendata_archiver' )}</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                {foreach $list as $item sequence array( 'bglight', 'bgdark' ) as $trClass}
                <tr class="{$trClass}">
                    <td>{$item.type_name}</td>
                    <td>{$item.requested_time|l10n( 'shortdatetime' )}</td>
                    <td>{$item.user_name}</td>                
                    <td>{$item.status_name}</td>   
                    <td>{$item.object_count}</td>     
                    <td><a class="btn btn-xs btn-info" href="{concat($module_uri,'/',$item.requested_time,'/',$item.user_id)|ezurl(no)}">{"Detail"|i18n( 'extension/ocopendata_archiver' )}</a></td>
                </tr>
                {/foreach}
            </tbody>
        </table>
        <p>&nbsp;</p>
    {/if}
    </div>
{/if}

<div class="context-toolbar">
    {include name=navigator uri='design:navigator/google.tpl'
                            page_uri=$module_uri
                            item_count=$list_count
                            view_parameters=$view_parameters
                            item_limit=$limit}
</div>