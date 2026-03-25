---
title: " The Inaugural Post"
author: "Bill Whorton"
date: "03-25-2026"
description: "After about three days, I'm happy to say that this site is more or less complete. That's not to say that I'm not going to keep tinkering with it, but at this point every part of the site looks like it's supposed to look, and there's content, if not a ton. It's a modest accomplishment, I know, but I'm pretty proud of how it turned out, and I'm going to go into details here."
slug: "inaugural-post"
---
## The Inaugural Post

![Christening a ship](../../static/images/smash-bottle-ship.png "I believe this is customary at this stage.")

After about three days, I'm happy to say that this site is more or less complete. That's not to say that I'm not going to keep tinkering with it, but at this point every part of the site looks like it's supposed to look, and there's content, if not a ton. It's a modest accomplishment, I know, but I'm pretty proud of how it turned out, and I'm going to go into details here.

My old site was a WordPress site that I set up mostly so I didn't have to worry about it. I wanted a turn-key solution, and I had entertained the idea of learning WP templating as a side-hustle. That didn't pan out, in no small part because WordPress is extremely opinionated about things like file names and directory structure, and I quickly ran out of patience. Also, as I've said elsewhere, I am not a fan of PHP, and WordPress is about as PHP as you can get.

All that said, I will still advocate for WordPress—not that they need my help—because it's well-supported, easy to use as CMSs go, and has a huge ecosystem. If you don't want to touch code, and you just want to generate content, WordPress is a great choice. It's when you want to customize anything that it starts going south. And, to be perfectly honest, it starts bloated and steadily gets worse over time. 

My thinking at the time was that I'd just stick WordPress on my vps, pick a theme, throw up some links to work I've done, my résumé, etc., and maybe post an article every now and again. And it worked well enough for a while. Eventually, however, the bloat caught up, and, between inconsistent posting, a distinct lack of focus as to the site's purpose, and the dated look of the theme, I abandoned it.

When I stopped listing it as my personal website, I realized that the time had come for me to get serious about rebuilding it. I'd taken a swing at spinning up Django a few times, but never really followed through. Without a strong use-case, I was just kind of building a theoretical CMS for a theoretical website, with the requirements changing constantly in my head. Still, I knew I wanted to use Python, so I decided on Flask.

I like Flask because it's very lightweight. It just kind of hands you the basics and gets out of the way, which is all I really wanted. You can actually get to something like Django from Flask by adding a bunch of plugins. With Flask, like how React just handles rendering and leaves it to you to add what you need to build an application with it, the expectation is that you'll add what you need and nothing else. This avoids the bloat issue nicely and keeps you from being overwhelmed. 

My intention was to avoid making an actual CMS. I didn't want to add content directly to templates, though, if I could avoid it. So, I decide on Flask-Flatpages, a plugin which takes Markdown files and renders them as HTML. This makes it easy to generate content without needing a database or CMS, and it allows for the flexibility that you'd get from a rich text editor. 

Although I've set up WSGI on Apache servers in the past, I didn't want to deal with the headache this time around. After all, I wanted to keep this simple. So, I decided to use Frozen-Flask to generate a static site and upload the files to my server. While this does require SFTPing files to the server, which is not quite the deployment process I wanted, it's simple enough and for my purposes not a problem. It does have the advantage of being very fast and uncomplicated.

In the interests of keeping things light, I avoided JavaScript so I could avoid shipping scripts. I am using TailwindCSS, which is the best thing since sliced bread and handily generates a CSS file that is included in the static build. The heaviest parts of the site are the images and fonts, but I'll optimize the images eventually and, in the case of the fonts, I think it's worth it. The impact on performance is minimal. 

And so, the result is a cleaner design, a faster, more focused site that's more suited to a professional software engineer. 



