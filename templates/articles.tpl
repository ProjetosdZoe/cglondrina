{{#post}}
<div class='blog-block' data-news-block >
    <div class='date'>
        <div class='day'>{{day}}</div>
        <div class='other'>{{posted}}</div>
    </div>
    <img src='/assets/frontend/images/articles/{{image}}' alt=''>
    <a class='name' href='/artigos/post/{{urlrequest}}'>{{title}}</a>
    <div class='position'>{{category}}</div>
    {{{text}}}
    <div class='clearfix'>
        <a class='pull-left read-more' href='/artigos/post/{{urlrequest}}'>
            <em class='fa fa-chevron-right'></em>
            Continue Lendo
        </a>
    </div>
    <hr>
</div>
{{/post}}

{{#testimony}}
<div class='blog-block' data-testimony-block >
    <div class='date'>
        <div class='day'>{{day}}</div>
        <div class='other'>{{posted}}</div>
    </div>
    <a class='name' href='/testemunhos/post/{{urlrequest}}'>{{title}}</a>
    <div class='position'>{{name}}</div>
    {{{text}}}
    <div class='clearfix'>
        <a class='pull-left read-more' href='/testemunhos/post/{{urlrequest}}'>
            <em class='fa fa-chevron-right'></em>
            Continue Lendo
        </a>
    </div>
    <hr>
</div>
{{/testimony}}

{{#comment}}
<div class="comment">
    <div class="comments-details">
        <div class="about-auther">
            <a href="#" class="name pull-left">{{name}} :</a>
            <div class="date pull-right">{{date}}</div>
        </div>
        <p>{{comment}}</p>
    </div>
</div>
{{/comment}}