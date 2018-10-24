<div class="message-warning alert alert-danger">
    <p>{'This content was archived on %1'|i18n( 'extension/ocopendata_archiver',, array($archive_item.requested_time|l10n( 'shortdate' )) )}</p>
</div>
{foreach $class.fields as $field}
{if and(is_set($data[$field.identifier]), $data[$field.identifier])}
	<div class="row" style="margin-bottom:10px">
		<div class="col-md-2">
			<strong>{$field.name[$locale]}</strong>
		</div>
		<div class="col-md-10">
			{if $data[$field.identifier]|is_array()}
				<ul class="list-unstyled">
				{foreach $data[$field.identifier] as $item}
					<li>
					{if $item|is_array()}
						{foreach $item as $subitem}
							{$subitem|wash} 
						{/foreach}
					{else}
						{$item|wash}
					{/if}					
					</li>
				{/foreach}
				</ul>
			{elseif array('ezbinaryfile','ezimage')|contains($field.dataType)}
				<a href="{$data[$field.identifier]|ezurl('no')}">Download</a>
			{else}
				{$data[$field.identifier]}
			{/if}
		</div>
	</div>
{/if}
{/foreach}