using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.Networking;

public class TaoYeuCau : MonoBehaviour
{
    public InputField maVanDon;
    public Dropdown type;
    public InputField noidung;

    public void Load()
    {
        gameObject.SetActive(true);
        maVanDon.text = "";
        type.value = 0;
        noidung.text = "";
    }
    public void GuiYeuCau()
    {
        StartCoroutine(GuiHandler());
    }

    IEnumerator GuiHandler()
    {
        // * Data field
        WWWForm form = new WWWForm();
        form.AddField("add_request", "");
        form.AddField("order_id", maVanDon.text);
        form.AddField("user_id", Manager.instance.userId);
        form.AddField("request_type", type.value);
        form.AddField("content", noidung.text);

        // * URL
        string url = "http://localhost/php/sp_request.php";

        // * Request handler
        using (UnityWebRequest www = UnityWebRequest.Post(url, form))
        {
            yield return www.SendWebRequest();

            Debug.Log(www.downloadHandler.text);

            string[] result = www.downloadHandler.text.Split('\n');

            // * Request Success
            if (result[0] == "0")
            {
                QuanLyYeuCau.instance.Load();
                gameObject.SetActive(false);
                Debug.Log("Tạo yêu cầu thành công");
            }
            else if (result[0] == "1")
            {
                Manager.instance.Alert("Mã vận đơn không tồn tại");
            }
            else // * Request fail
            {
                Debug.Log("Tạo yêu cầu thất bại : " + result[0]);
            }
        }
    }
}
