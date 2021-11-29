using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.EventSystems;
public class ButtonHandler : MonoBehaviour, IPointerDownHandler, IPointerEnterHandler, IPointerExitHandler
{
    public Color exitColor;
    public Color enterColor;
    public Color activeColor;
    public Image image;
    public GameObject panel;
    public bool active = false;
    void Start()
    {
        image = GetComponent<Image>();
        image.color = exitColor;
    }

    public virtual void PointerDownHandler()
    {

    }
    public void OnPointerDown(PointerEventData pointerEventData)
    {
        if (!active)
        {
            Manager.instance.ResetActiveButton();
            active = true;
            image.color = activeColor;

            PointerDownHandler();

            if (panel != null)
                panel.SetActive(true);
        }
    }
    public void OnPointerEnter(PointerEventData pointerEventData)
    {
        if (!active)
            image.color = enterColor;
    }
    public void OnPointerExit(PointerEventData pointerEventData)
    {
        if (!active)
            image.color = exitColor;
    }
}
