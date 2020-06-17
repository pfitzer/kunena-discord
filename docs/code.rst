Code Documentation
==================

Classes
-------

plgKunenaDiscord Class
......................

.. php:class:: plgKunenaDiscord

  plgKunenaDiscord class

  .. php:method:: __construct($subject, array $config = array())

      ConstructorMethod

      :param str $subject:
      :param array $config:


  .. php:method:: onKunenaGetActivity()

      Get Kunena activity stream integration object.

      :return: :php:class:`KunenaDiscord`


KunenaDiscord Class
...................

.. php:class:: KunenaDiscord

	KunenaDiscord class

	.. php:method:: __construct(array $webhooks, $domain)

		Constructor

		:param array $webhooks: the configured webhooks
		:param str $domain: the configured domain

	.. php:method:: onAfterReply($message)

		Prepares and sends the messages after a reply is made

		:param KunenaForumMessage $message: the Kunena Message

	.. php:method:: onAfterPost($message)

		Prepares and sends the messages after a new post is made

		:param KunenaForumMessage $message: the Kunena Message
