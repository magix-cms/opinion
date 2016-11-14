{extends file="layout.tpl"}
{block name="title"}{seo_rewrite config_param=['level'=>'0','idmetas'=>'1','default'=>#seo_t_static_opinion#]}{/block}
{block name="description"}{seo_rewrite config_param=['level'=>'0','idmetas'=>'2','default'=>#seo_d_static_opinion#]}}{/block}
{block name='body:id'}opinion{/block}

{block name='article'}
    <article id="article" class="col-xs-12">
        {block name="article:content"}
            <header>
                <h1>{#opinion_root#|ucfirst}</h1>
                <div id="global-note">
                    <p class="lead">{#global_rating#|ucfirst}</p>
                    <p class="lead">
                        <span class="average">{$globalRating.globalRating}</span>
                        <span class="max-value">/5</span>
                        <span class="fa fa-star rate"></span>
                    </p>
                </div>
            </header>
            <div class="opinion-list">
                {if !empty($opinions)}
                    {foreach $opinions as $op}
                        {include file="opinion/loop/op.tpl"}
                    {/foreach}
                {/if}
            </div>
            {if $pages && $pages.last_page > 1}
                <div id="pager">
                    <ul class="pagination">
                        {for $p=1 to $pages.last_page}
                            {if $p == 1}
                                <li{if !$smarty.get.page} class="active"{/if}>
                                    <a href="{geturl}/{$smarty.request.strLangue}/{$smarty.request.magixmod}" title="{#show_page#}">{$p}</a>
                                </li>
                            {else}
                                <li{if $smarty.get.page == $p} class="active"{/if}>
                                    <a href="{geturl}/{$smarty.request.strLangue}/{$smarty.request.magixmod}/page/{$p}" title="{#show_page#}">{$p}</a>
                                </li>
                            {/if}
                        {/for}
                    </ul>
                </div>
            {/if}
        {/block}
    </article>
{/block}

{block name="aside"}{/block}