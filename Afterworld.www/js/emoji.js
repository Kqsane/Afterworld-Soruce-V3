// This makes all emojis work
(function () {
  const emojiMap = {
    ":xqcL:": "https://i.redd.it/yakhrtg6nm1a1.png",
    ":TetoKid:": "https://devopstest1.aftwld.xyz/Images/Emoji/TetoKid.png",
    ":Pekora:": "https://devopstest1.aftwld.xyz/Images/Emoji/Pekora.png",
    ":PekoraTired:": "https://devopstest1.aftwld.xyz/Images/Emoji/PekoraTired.png",
    ":ConfusedPekora:": "https://devopstest1.aftwld.xyz/Images/Emoji/ConfusedPekora.png",
    ":Transgender:": "https://devopstest1.aftwld.xyz/Images/Emoji/Transgender.png",
    ":Pansexual:": "https://devopstest1.aftwld.xyz/Images/Emoji/Pansexual.png",
    ":NonBinary:": "https://devopstest1.aftwld.xyz/Images/Emoji/NonBinary.png",
    ":Lesbian:": "https://devopstest1.aftwld.xyz/Images/Emoji/Lesbian.png",
    ":Bisexual:": "https://devopstest1.aftwld.xyz/Images/Emoji/Bisexual.png",

  };

  function getEmoteHTML(code, url) {
    return `<img src="${url}" alt="${code}" title="${code}" style="width:24px; height:24px; vertical-align:middle; margin-left:6px;">`;
  }

  function escapeRegExp(str) {
    return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  }

  function replaceTextWithEmotes(node) {
    if (node.nodeType === Node.TEXT_NODE) {
      let originalText = node.nodeValue;
      let hasMatch = false;

      for (const [code, url] of Object.entries(emojiMap)) {
        if (originalText.includes(code)) {
          const emoteHTML = getEmoteHTML(code, url);
          const pattern = new RegExp(escapeRegExp(code), 'g');
          originalText = originalText.replace(pattern, emoteHTML);
          hasMatch = true;
        }
      }

      if (hasMatch) {
        const span = document.createElement('span');
        span.innerHTML = originalText;
        node.replaceWith(span);
      }
    } else if (
      node.nodeType === Node.ELEMENT_NODE &&
      !["SCRIPT", "STYLE", "TEXTAREA", "INPUT"].includes(node.tagName)
    ) {
      for (const child of Array.from(node.childNodes)) {
        replaceTextWithEmotes(child);
      }
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    replaceTextWithEmotes(document.body);
    const observer = new MutationObserver((mutations) => {
      for (const mutation of mutations) {
        for (const node of mutation.addedNodes) {
          replaceTextWithEmotes(node);
        }
      }
    });

    observer.observe(document.body, {
      childList: true,
      subtree: true
    });
  });
})();
