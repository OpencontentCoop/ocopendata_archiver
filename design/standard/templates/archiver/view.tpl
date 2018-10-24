{foreach $class.fields as $field}
{if and(is_set($data[$field.identifier]), $data[$field.identifier])}
	<div class="row" style="margin-bottom:10px">
		<div class="col-md-2">
			<strong>{$field.name[$locale]}</strong>
		</div>
		<div class="col-md-10">
			{if is_array($data[$field.identifier])}
				{$data[$field.identifier]|implode('<br />')}
			{elseif array('ezbinaryfile','ezimage')|contains($field.dataType)}
				<a href="{$data[$field.identifier]|ezurl('no')}">Download</a>
			{else}
				{$data[$field.identifier]}
			{/if}
		</div>
	</div>
{/if}
{/foreach}