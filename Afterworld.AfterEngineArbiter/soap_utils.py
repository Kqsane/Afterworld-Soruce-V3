import os
import random
from zeep import Client
from zeep.transports import Transport
from requests import Session
from base64 import b64decode

class SoapUtils:
    def __init__(self):
        wsdl_path = os.path.abspath('C:/ArbiterSite/config/classes/rcc/rccservice.wsdl')
        session = Session()
        self.raw_client = Client(wsdl=wsdl_path, transport=Transport(session=session))
        self.client = self.raw_client.create_service(
            '{http://devopstest1.aftwld.xyz/}RCCServiceSoap',
            'http://127.0.0.1:64989'
        )

    def _generate_job_id(self):
        def mt_rand(low, high):
            return random.randint(low, high)

        return '{:04x}{:04x}-{:04x}-{:04x}-{:04x}-{:04x}{:04x}{:04x}'.format(
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        )

    def render_user(self, user_id):
        script = f'''local userId = {user_id}
local ThumbnailGenerator = game:GetService("ThumbnailGenerator")
return ThumbnailGenerator:Click("PNG", 352, 352, true)'''
        return self._run_script_and_save(script, f"RenderedUsers/{user_id}.png")

    def render_user3d(self, user_id):
        script = f'''local userId = {user_id}
local ThumbnailGenerator = game:GetService("ThumbnailGenerator")
return ThumbnailGenerator:Click3D("PNG", 352, 352, true)'''
        return self._run_script_and_save(script, f"RenderedUsers/{user_id}.png")

    def render_asset(self, asset_id, asset_type):
        script = f'''local assetId = {asset_id}
local assetType = {asset_type}
local ThumbnailGenerator = game:GetService("ThumbnailGenerator")
return ThumbnailGenerator:ClickAsset(assetType, assetId, "PNG", 352, 352, true)'''
        return self._run_script_and_save(script, f"RenderedAssets/{asset_id}.png")

    def get_all_rccs(self):
        return {"jobs": self.client.GetAllJobs()}

    def _run_script_and_save(self, script_text, output_path):
        job_id = self._generate_job_id()

        job = self.raw_client.get_type("ns0:Job")(
            id=job_id,
            expirationInSeconds=60
        )

        script = self.raw_client.get_type("ns0:ScriptExecution")(
            name="Script",
            script=script_text,
            arguments={"LuaValue": []}
        )

        self.client.OpenJob(job=job)
        try:
            result = self.client.Execute(job=job, script=script)
            return self._save_image(result, output_path)
        finally:
            self.client.CloseJob(job=job)

    def _save_image(self, result, path):
        if not result or not isinstance(result, str):
            raise ValueError("Invalid render result")

        decoded = b64decode(result)
        full_path = os.path.join("C:/cdn/thumbs", path)
        os.makedirs(os.path.dirname(full_path), exist_ok=True)

        with open(full_path, "wb") as f:
            f.write(decoded)

        return f"saved:{path}"
