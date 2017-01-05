{if !isset($root)}{$root = false}{/if}
<blockquote class="opinion">
    <span class="quote"></span>
    <div class="opinion-rating pull-right">
        {for $i=1 to $op.rating_opinion}
            <span class="fa fa-star rate"></span>
        {/for}
        {for $i=$op.rating_opinion+1 to 5}
            <span class="fa fa-star"></span>
        {/for}
    </div>
    <p>{$op.msg_opinion}</p>
    <footer>{#by#}
        <span>{#opinion_by#|ucfirst} {$op.pseudo_opinion}</span>, le
        <time datetime="{$op.posted}">{$op.date_opinion|date_format:"%d %B %Y"}</time>
        {if $root}sur <a title="{#show#|ucfirst} " href="{$op.url}">{$op.name}</a>{/if}
    </footer>
</blockquote>