"""Stuff that after 30 years, you'd think Python would provide out-of-the-box?"""

# ... Or at least in a module?
# https://stackoverflow.com/a/13624858/435004
class classproperty(object):

    def __init__(self, fget):
        self.fget = fget

    def __get__(self, owner_self, owner_cls):
        return self.fget(owner_cls)

# ... Or with a name that makes sense?
import more_itertools

def sole(iterator):
    return more_itertools.one(iterator)

def sole_or_none(iterator):
    return more_itertools.first(iterator, None)