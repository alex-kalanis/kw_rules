kw_rules
================

Contains simplification of rules from the whole bunch of setting. Allow you
use either get and cli or server and env params as same source.

This is the mixed package - contains sever-side implementation in Python and PHP.

# PHP Installation

```
{
    "require": {
        "alex-kalanis/kw_rules": "1.0"
    }
}
```

(Refer to [Composer Documentation](https://github.com/composer/composer/blob/master/doc/00-intro.md#introduction) if you are not
familiar with composer)


# PHP Usage

1.) Use your autoloader (if not already done via Composer autoloader)

2.) Connect the "kw_rules" into your app. When it came necessary
you can extends every library to comply your use-case; mainly your captcha init
and set checks itself.

# Python Installation

into your "setup.py":

```
    install_requires=[
        'kw_rules',
    ]
```

# Python Usage

1.) Connect the "kw_rules.rules" into your app. When it came necessary
you can extends every library to comply your use-case; mainly your captcha init
and set checks itself.
