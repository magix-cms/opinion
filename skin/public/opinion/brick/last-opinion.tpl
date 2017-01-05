{if !isset($adjust)}
    {assign var="adjust" value="clip"}
{/if}
<section id="last-opinion"{if $adjust == 'fluid'} class="section-block container-fluid"{/if}>
    {if $adjust == 'clip'}
    <div class="container">
        {/if}
        <h3><a href="{geturl}/{getlang}/opinion/" title="{#opinion_intro#|ucfirst}">{#opinions#|ucfirst}</a></h3>
        {widget_catalog_data
            conf =[
                'context'   => 'product',
                'sort'      => 'opinion',
                'limit'     => 1,
                'plugins' => [
                    'override'  => 'plugins_opinion_public',
                    'item' => [
                        'pseudo_opinion'    =>  'pseudo_opinion',
                        'email_opinion'     =>  'email_opinion',
                        'msg_opinion'       =>  'msg_opinion',
                        'rating_opinion'    =>  'rating_opinion',
                        'date_opinion'      =>  'date_opinion'
                        ]
                    ]
            ]
            assign='opinions'
        }
        <div class="opinion-list">
        {if !empty($opinions)}
        {foreach $opinions as $op}
            {include file="opinion/loop/op.tpl" root=true}
        {/foreach}
        {else}
            <p class="lead">Il n'y a aucun témoignage pour l'instant.</p>
        {/if}
        </div>
        {if $adjust == 'clip'}
        <p class="text-center"><a href="{geturl}/{getlang}/opinion/" title="{#opinion_intro#|ucfirst}" class="btn btn-box btn-invert btn-fr-theme">{#opinion_btn#|ucfirst}</a></p>
    </div>
    {/if}
</section>